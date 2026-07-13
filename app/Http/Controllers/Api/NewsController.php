<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wordpress\WpPost;
use App\Traits\SeoFormatterTrait;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;

class NewsController extends Controller
{
    use SeoFormatterTrait;

    #[OA\Get(path: '/api/news', summary: 'Danh sách News (Tổng)', tags: ['News'])]
    #[OA\Parameter(name: 'locale', in: 'query', schema: new OA\Schema(type: 'string', default: 'ja'))]
    #[OA\Parameter(name: 'featured', in: 'query', schema: new OA\Schema(type: 'boolean'))]
    #[OA\Parameter(name: 'category', in: 'query', schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'page', in: 'query', schema: new OA\Schema(type: 'integer', default: 1))]
    #[OA\Response(response: 200, description: 'Lấy dữ liệu thành công')]
    public function index(Request $request): JsonResponse
    {
        $locale = $this->validateLocale($request->query('locale'));
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
                'meta' => ['total' => $news->count()],
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
    public function getByCategory(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $this->getCategoryCounts(),
        ]);
    }

    #[OA\Get(path: '/api/news/{slug}', summary: 'Chi tiết News', tags: ['News'])]
    #[OA\Parameter(name: 'slug', in: 'path', required: true, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'locale', in: 'query', schema: new OA\Schema(type: 'string', default: 'ja'))]
    #[OA\Response(response: 200, description: 'Lấy dữ liệu thành công')]
    #[OA\Response(response: 404, description: 'Không tìm thấy bài viết')]
    public function show($slug, Request $request): JsonResponse
    {
        $locale = $this->validateLocale($request->query('locale'));

        $post = $this->getBaseNewsQuery()->bySlug($slug)->first();

        if (! $post) {
            return response()->json([
                'status' => 'error',
                'message' => 'News not found',
            ], 404);
        }

        // Tái sử dụng logic transform chung để tránh lặp code (DRY)
        $formatted = $this->transformPostData($post, $locale, collect());

        // Bổ sung các trường chỉ detail mới có
        $meta = $post->meta->pluck('meta_value', 'meta_key');
        $formatted['content'] = $meta['content_'.$locale] ?? $meta['content_ja'] ?? $post->post_content;
        $formatted['seo'] = $this->buildSeoData($post, $meta, $locale, 'news');

        return response()->json([
            'status' => 'success',
            'data' => $formatted,
        ], 200);
    }

    /**
     * Định dạng danh sách News và tối ưu Query hình ảnh chống lỗi N+1
     */
    private function formatNews($newsCollection, $locale)
    {
        // Bước 1: Gom tất cả các Image ID dạng số của cả danh sách lại thành một mảng độc nhất
        $imageIds = $newsCollection->map(function ($item) {
            return $item->meta->where('meta_key', 'image')->first()?->meta_value;
        })->filter(fn ($id) => is_numeric($id))->unique()->toArray();

        // Bước 2: Chỉ chạy DUY NHẤT 1 câu query để lôi hết link ảnh của cả list ra (Eager-like loading)
        $attachments = empty($imageIds) ? collect() : DB::table('wp_posts')
            ->whereIn('ID', $imageIds)
            ->where('post_type', 'attachment')
            ->pluck('guid', 'ID');

        // Bước 3: Map data sạch sẽ, mượt mà mà không dính thêm bất kỳ query nào phát sinh
        return $newsCollection->map(function ($item) use ($locale, $attachments) {
            return $this->transformPostData($item, $locale, $attachments);
        });
    }

    /**
     * Hàm dùng chung (Shared Transformer) để parse dữ liệu thô từ WP sang chuẩn API sạch
     */
    private function transformPostData($item, $locale, $attachments)
    {
        $meta = $item->meta->pluck('meta_value', 'meta_key');
        $imageId = $meta['image'] ?? null;

        // Lấy link ảnh từ mảng attachments đã gom trước đó, hoặc fallback lại string thường
        $imageUrl = is_numeric($imageId) ? ($attachments[$imageId] ?? '') : ($imageId ?? '');
        $term = $item->terms->first();

        return [
            'id' => $item->ID,
            'title' => $meta['title_'.$locale] ?? $meta['title_ja'] ?? $item->post_title,
            'subtitle' => $meta['subtitle_'.$locale] ?? $meta['subtitle_ja'] ?? '',
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
    }

    private function getBaseNewsQuery()
    {
        return WpPost::query()
            ->where('post_type', 'news')
            ->where('post_status', 'publish')
            ->with(['meta', 'terms']);
    }

    private function validateLocale($locale): string
    {
        return in_array($locale, ['ja', 'vi', 'en'], true) ? $locale : 'ja';
    }

    /**
     * 💡 Tip nâng cao: Đoạn này chạy Join 4 bảng rất nặng.
     * Sau này đi làm thực tế khuyên bro nên bỏ vào Cache (ví dụ Cache::remember trong 1 tiếng) để tối ưu tối đa.
     */
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
}
