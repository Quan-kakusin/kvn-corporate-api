<?php

namespace App\Http\Controllers;

use App\Models\Wordpress\WpPost;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;

class NewsController extends Controller
{
    #[OA\Get(path: '/api/news', summary: 'Danh sách News (Tổng)', tags: ['News'])]
    #[OA\Parameter(name: 'locale', in: 'query', schema: new OA\Schema(type: 'string', default: 'ja'))]
    #[OA\Parameter(name: 'featured', in: 'query', schema: new OA\Schema(type: 'boolean'))]
    #[OA\Parameter(name: 'category', in: 'query', schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'page', in: 'query', schema: new OA\Schema(type: 'integer', default: 1))]
    #[OA\Response(response: 200, description: 'Lấy dữ liệu thành công')]
    public function index(Request $request)
    {
        $locale = $request->query('locale', 'ja');
        $isFeatured = filter_var($request->query('featured'), FILTER_VALIDATE_BOOLEAN);
        $categorySlug = $request->query('category');


        $query = $this->getBaseNewsQuery();

        if ($categorySlug && $categorySlug !== 'all') {
            $query->whereHas('terms', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        if ($isFeatured) {
            $query->whereHas('meta', function ($q) {
                $q->where('meta_key', 'is_featured')->where('meta_value', 1);
            });
            $news = $query->orderBy('post_date', 'desc')->take(3)->get();
            return response()->json([
                'status' => 'success',
                'data' => $this->formatNews($news, $locale),
                'category_counts' => $this->getCategoryCounts(),
                'meta' => ['total' => $news->count()]
            ]);
        }

        $news = $query->orderBy('post_date', 'desc')->paginate(5);
        return response()->json([
            'status' => 'success',
            'data' => $this->formatNews($news->getCollection(), $locale),
            'category_counts' => $this->getCategoryCounts(),
            'meta' => [
                'current_page' => $news->currentPage(),
                'last_page' => $news->lastPage(),
                'per_page' => 5,
                'total' => $news->total(),
            ],
        ]);
    }

    #[OA\Get(path: '/api/news/categories', summary: 'Danh sách Category kèm số lượng bài viết', tags: ['News'])]
    #[OA\Response(response: 200, description: 'Lấy dữ liệu thành công')]
    public function getByCategory(Request $request)
    {
        return response()->json([
            'data' => $this->getCategoryCounts(),
        ]);
    }

    private function getCategoryCounts()
    {
        $categories = DB::table('wp_term_relationships')
            ->join('wp_term_taxonomy', 'wp_term_relationships.term_taxonomy_id', '=', 'wp_term_taxonomy.term_taxonomy_id')
            ->join('wp_terms', 'wp_term_taxonomy.term_id', '=', 'wp_terms.term_id')
            ->join('wp_posts', 'wp_term_relationships.object_id', '=', 'wp_posts.ID')
            ->where('wp_posts.post_type', 'news')
            ->where('wp_posts.post_status', 'publish')
            ->select('wp_terms.term_id', 'wp_terms.name', 'wp_terms.slug', DB::raw('count(*) as total'))
            ->groupBy('wp_terms.term_id', 'wp_terms.name', 'wp_terms.slug')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->term_id,
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


    private function formatNews($newsCollection, $locale)
    {
        return $newsCollection->map(function ($item) use ($locale) {
            $meta = $item->meta->pluck('meta_value', 'meta_key');

            $imageId = $meta['image'] ?? null;
            $imageUrl = '';

            if (is_numeric($imageId)) {
                $attachment = DB::table('wp_posts')
                    ->where('ID', $imageId)
                    ->where('post_type', 'attachment')
                    ->first();
                $imageUrl = $attachment ? $attachment->guid : '';
            } else {
                $imageUrl = $imageId ?? '';
            }

            $term = $item->terms->first();

            $title = $meta['title_' . $locale] ?? $meta['title_ja'] ?? $item->post_title;
            $subtitle = $meta['subtitle_' . $locale] ?? $meta['subtitle_ja'] ?? '';

            return [
                'id' => $item->ID,
                'title' => $title,
                'subtitle' => $subtitle,
                'image' => [
                    'url' => $imageUrl
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


    private function getBaseNewsQuery()
    {
        return WpPost::query()
            ->where('post_type', 'news')
            ->where('post_status', 'publish')
            ->with(['meta', 'terms']);
    }

    #[OA\Get(path: '/api/news/{slug}', summary: 'Chi tiết News', tags: ['News'])]
    #[OA\Parameter(name: 'slug', in: 'path', required: true, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'locale', in: 'query', schema: new OA\Schema(type: 'string', default: 'ja'))]
    #[OA\Response(response: 200, description: 'Lấy dữ liệu thành công')]
    #[OA\Response(response: 404, description: 'Không tìm thấy bài viết')]
    public function show($slug, Request $request)
    {
        $locale = $request->query('locale', 'ja');

        $post = WpPost::query()
            ->where('post_type', 'news')
            ->where('post_status', 'publish')
            ->bySlug($slug)
            ->with(['meta', 'terms']) // Bỏ translations
            ->first();

        if (!$post) {
            return response()->json([
                'status' => 'error',
                'message' => 'News not found',
            ], 404);
        }

        $meta = $post->meta->pluck('meta_value', 'meta_key');

        $imageId = $meta['image'] ?? null;
        $imageUrl = '';

        if (is_numeric($imageId)) {
            $attachment = DB::table('wp_posts')
                ->where('ID', $imageId)
                ->where('post_type', 'attachment')
                ->first();
            $imageUrl = $attachment ? $attachment->guid : '';
        } else {
            $imageUrl = $imageId ?? '';
        }

        $term = $post->terms->first();

        $title = $meta['title_' . $locale] ?? $meta['title_ja'] ?? $post->post_title;
        $content = $meta['content_' . $locale] ?? $meta['content_ja'] ?? $post->post_content;

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $post->ID,
                'title' => $title,
                'content' => $content,
                'image' => [
                    'url' => $imageUrl
                ],
                'slug' => urldecode($post->post_name),
                'created_at' => Carbon::parse($post->post_date)->format('Y.m.d'),
                'category' => $term ? [
                    'id' => $term->term_id,
                    'name' => $term->name,
                    'slug' => $term->slug,
                ] : null,
            ]
        ], 200);
    }
}
