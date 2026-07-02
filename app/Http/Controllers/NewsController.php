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
    #[OA\Parameter(name: 'locale', in: 'query', schema: new OA\Schema(type: 'string', default: 'vi'))]
    #[OA\Parameter(name: 'featured', in: 'query', schema: new OA\Schema(type: 'boolean'))]
    #[OA\Parameter(name: 'category', in: 'query', schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'page', in: 'query', schema: new OA\Schema(type: 'integer', default: 1))]
    #[OA\Response(response: 200, description: 'Lấy dữ liệu thành công')]
    public function index(Request $request)
    {
        $locale = $request->query('locale', 'vi');
        $isFeatured = filter_var($request->query('featured'), FILTER_VALIDATE_BOOLEAN);
        $categorySlug = $request->query('category');

        $query = $this->getBaseNewsQuery($locale);

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
                'data' => $this->formatNews($news),
                'category_counts' => $this->getCategoryCounts(),
                'meta' => ['total' => $news->count()]
            ]);
        }

        $news = $query->orderBy('post_date', 'desc')->paginate(5);
        return response()->json([
            'status' => 'success',
            'data' => $this->formatNews($news->getCollection()),
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
            ->select('wp_terms.name', 'wp_terms.slug', DB::raw('count(*) as total'))
            ->groupBy('wp_terms.name', 'wp_terms.slug')
            ->get()
            ->map(function ($item) {
                return [
                    'title' => $item->name,
                    'slug' => $item->slug,
                    'totalPosts' => $item->total,
                ];
            })
            ->toArray();

        // Calculate total across all categories
        $totalAll = array_sum(array_column($categories, 'totalPosts'));

        // Prepend 'All' category
        array_unshift($categories, [
            'title' => 'All',
            'slug' => 'all',
            'totalPosts' => $totalAll,
        ]);

        return $categories;
    }

    private function formatNews($newsCollection)
    {
        return $newsCollection->map(function ($item) {
            $meta = $item->meta->pluck('meta_value', 'meta_key');
            $imageMeta = $meta['image'] ?? '';
            $finalImage = is_numeric($imageMeta) ? (WpPost::find($imageMeta)->guid ?? '') : $imageMeta;

            $term = $item->terms->first();

            return [
                'id' => $item->ID,
                'title' => ($item->translations->first()->post_title ?? $item->post_title),
                'subtitle' => $meta['subtitle'] ?? '',
                'image' => $finalImage,
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

    private function getBaseNewsQuery($locale)
    {
        return WpPost::query()
            ->where('post_type', 'news')
            ->where('post_status', 'publish')
            ->with(['meta', 'terms', 'translations' => fn($q) => $q->where('locale', $locale)]);
    }
    #[OA\Get(path: '/api/news/{slug}', summary: 'Chi tiết News', tags: ['News'])]
    #[OA\Parameter(name: 'slug', in: 'path', required: true, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'locale', in: 'query', schema: new OA\Schema(type: 'string', default: 'vi'))]
    #[OA\Response(response: 200, description: 'Lấy dữ liệu thành công')]
    #[OA\Response(response: 404, description: 'Không tìm thấy bài viết')]

    public function show($slug, Request $request)
    {
        $locale = $request->query('locale', 'vi');

        $post = WpPost::query()
            ->where('post_type', 'news')
            ->where('post_status', 'publish')
            ->bySlug($slug)
            ->with(['meta', 'terms', 'translations' => fn($q) => $q->where('locale', $locale)])
            ->first();

        if (!$post) {
            return response()->json([
                'status' => 'error',
                'message' => 'News not found',
            ], 404);
        }

        $meta = $post->meta->pluck('meta_value', 'meta_key');
        $imageMeta = $meta['image'] ?? '';
        $finalImage = is_numeric($imageMeta) ? (WpPost::find($imageMeta)->guid ?? '') : $imageMeta;

        $term = $post->terms->first();
        $title = $post->translations->first()->post_title ?? $post->post_title;

        $content = $post->translations->first()->post_content ?? $post->post_content;

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $post->ID,
                'title' => $title,
                'content' => $content,
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
