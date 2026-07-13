<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wordpress\WpPost;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;
use App\Traits\SeoFormatterTrait;

class ProductController extends Controller
{
    use SeoFormatterTrait;

    #[OA\Get(path: '/api/products', summary: 'Danh sách Product (Tổng/Phân trang)', tags: ['Products'])]
    #[OA\Parameter(name: 'locale', in: 'query', schema: new OA\Schema(type: 'string', default: 'ja'))]
    #[OA\Parameter(name: 'featured', in: 'query', schema: new OA\Schema(type: 'boolean'))]
    #[OA\Parameter(name: 'category', in: 'query', schema: new OA\Schema(type: 'string', default: 'all'))]
    #[OA\Parameter(name: 'page', in: 'query', schema: new OA\Schema(type: 'integer', default: 1))]
    #[OA\Response(response: 200, description: 'Lấy dữ liệu thành công')]
    public function index(Request $request)
    {
        $locale = $request->query('locale', 'ja');
        $isFeatured = filter_var($request->query('featured'), FILTER_VALIDATE_BOOLEAN);
        $categorySlug = $request->query('category');

        $query = $this->getBaseProductQuery();

        if ($categorySlug && $categorySlug !== 'all') {
            $query->whereHas('terms', function ($q) use ($categorySlug) {
                $q->join('wp_term_taxonomy', 'wp_terms.term_id', '=', 'wp_term_taxonomy.term_id')
                    ->where('wp_terms.slug', $categorySlug)
                    ->where('wp_term_taxonomy.taxonomy', 'product_category');
            });
        }

        if ($isFeatured) {
            $query->whereHas('meta', function ($q) {
                $q->where('meta_key', 'is_featured')->where('meta_value', 1);
            });
            $products = $query->orderBy('post_date', 'desc')->take(3)->get();

            return response()->json([
                'status' => 'success',
                'data' => $this->formatProduct($products, $locale),
                'category_counts' => $this->getCategoryCounts(),
                'meta' => ['total' => $products->count()],
            ]);
        }

        $products = $query->orderBy('post_date', 'desc')->paginate(6);

        return response()->json([
            'status' => 'success',
            'data' => $this->formatProduct($products->getCollection(), $locale),
            'category_counts' => $this->getCategoryCounts(),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => 6,
                'total' => $products->total(),
            ],
        ]);
    }

    #[OA\Get(path: '/api/products/{slug}', summary: 'Chi tiết Product', tags: ['Products'])]
    #[OA\Parameter(name: 'slug', in: 'path', required: true, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'locale', in: 'query', schema: new OA\Schema(type: 'string', default: 'ja'))]
    #[OA\Response(response: 200, description: 'Lấy dữ liệu chi tiết thành công')]
    #[OA\Response(response: 404, description: 'Không tìm thấy sản phẩm')]
    public function show(Request $request, $slug)
    {
        $locale = $request->query('locale', 'ja');
        if (!in_array($locale, ['ja', 'vi', 'en'])) {
            $locale = 'ja';
        }

        $post = WpPost::query()
            ->where('post_type', 'products')
            ->where('post_status', 'publish')
            ->bySlug($slug)
            ->with(['meta', 'terms'])
            ->first();

        if (! $post) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found',
            ], 404);
        }

        $meta = $post->meta->pluck('meta_value', 'meta_key');

        $imageId = $meta['product_image'] ?? '';
        $imageUrl = $this->getImageUrl($imageId);

        $term = $post->terms->where('taxonomy', 'product_category')->first() ?? $post->terms->first();

        $title = $meta['title_' . $locale] ?? $meta['title_ja'] ?? $post->post_title;
        $subtitle = $meta['subtitle_' . $locale] ?? $meta['subtitle_ja'] ?? '';
        $content = $meta['content_' . $locale] ?? $meta['content_ja'] ?? $post->post_content;

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $post->ID,
                'title' => $title,
                'subtitle' => $subtitle,
                'content' => $content,
                'image' => [
                    'url' => $imageUrl,
                ],
                'slug' => urldecode($post->post_name),
                'created_at' => Carbon::parse($post->post_date)->format('Y.m.d'),
                'category' => $term ? [
                    'id' => $term->term_id,
                    'name' => $term->name,
                    'slug' => $term->slug,
                ] : null,
                'seo' => $this->buildSeoData($post, $meta, $locale, 'products')
            ],
        ], 200);
    }

    private function getBaseProductQuery()
    {
        return WpPost::query()
            ->where('post_type', 'products')
            ->where('post_status', 'publish')
            ->with(['meta', 'terms']);
    }

    private function getCategoryCounts()
    {
        $categories = DB::table('wp_term_relationships')
            ->join('wp_term_taxonomy', 'wp_term_relationships.term_taxonomy_id', '=', 'wp_term_taxonomy.term_taxonomy_id')
            ->join('wp_terms', 'wp_term_taxonomy.term_id', '=', 'wp_terms.term_id')
            ->join('wp_posts', 'wp_term_relationships.object_id', '=', 'wp_posts.ID')
            ->where('wp_posts.post_type', 'products')
            ->where('wp_posts.post_status', 'publish')
            ->where('wp_term_taxonomy.taxonomy', 'product_category')
            ->select('wp_terms.term_id', 'wp_terms.name', 'wp_terms.slug', DB::raw('count(*) as total'))
            ->groupBy('wp_terms.term_id', 'wp_terms.name', 'wp_terms.slug')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => (int) $item->term_id,
                    'title' => $item->name,
                    'slug' => $item->slug,
                    'totalPosts' => $item->total,
                ];
            })
            ->toArray();

        $totalAll = array_sum(array_column($categories, 'totalPosts'));

        array_unshift($categories, [
            'id' => 0,
            'title' => 'All',
            'slug' => 'all',
            'totalPosts' => $totalAll,
        ]);

        return $categories;
    }

    private function formatProduct($productCollection, $locale)
    {
        return $productCollection->map(function ($item) use ($locale) {
            $meta = $item->meta->pluck('meta_value', 'meta_key');

            $imageId = $meta['product_image'] ?? '';
            $imageUrl = $this->getImageUrl($imageId);

            $term = $item->terms->where('taxonomy', 'product_category')->first() ?? $item->terms->first();

            $title = $meta['title_' . $locale] ?? $meta['title_ja'] ?? $item->post_title;
            $subtitle = $meta['subtitle_' . $locale] ?? $meta['subtitle_ja'] ?? '';

            return [
                'id' => $item->ID,
                'title' => $title,
                'subtitle' => $subtitle,
                'image' => [
                    'url' => $imageUrl,
                ],
                'is_featured' => (bool) ($meta['is_featured'] ?? false),
                'slug' => urldecode($item->post_name),
                'created_at' => Carbon::parse($item->post_date)->format('Y.m.d'),
                'category' => $term ? [
                    'id' => $term->term_id,
                    'name' => $term->name,
                    'slug' => $term->slug,
                ] : null,
            ];
        });
    }

    private function getImageUrl($imageId)
    {
        if (! $imageId) {
            return '';
        }

        if (is_numeric($imageId)) {
            $attachment = DB::table('wp_posts')
                ->where('ID', $imageId)
                ->where('post_type', 'attachment')
                ->first();

            return $attachment ? $attachment->guid : '';
        }

        return $imageId;
    }
}
