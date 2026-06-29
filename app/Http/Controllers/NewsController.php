<?php

namespace App\Http\Controllers;

use App\Models\Wordpress\WpPost;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;

class NewsController extends Controller
{
    #[OA\Get(
        path: '/api/news',
        summary: 'News',
        tags: ['News']
    )]
    #[OA\Parameter(
        name: 'locale',
        description: "Mã ngôn ngữ (vi, ja, en). Mặc định là 'vi'",
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'string', default: 'vi')
    )]
    #[OA\Parameter(
        name: 'featured',
        description: 'Lọc theo bài viết nổi bật (truyền true để lọc)',
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'boolean')
    )]
    #[OA\Parameter(
        name: 'category',
        description: 'Lọc theo danh mục (slug: press-release, event, insight)',
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Response(
        response: 200,
        description: 'Lấy dữ liệu thành công',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'id', type: 'integer', example: 21),
                            new OA\Property(property: 'title', type: 'string', example: 'ソフトウェアエンジニアのためのClean Architecture入門'),
                            new OA\Property(property: 'subtitle', type: 'string', example: 'MASTERING PROFESSIONAL DEVELOPMENT'),
                            new OA\Property(property: 'image', type: 'string', example: 'http://localhost:8080/wp-content/uploads/2026/06/1-1.jpg'),
                            new OA\Property(property: 'link', type: 'string', example: 'https://dev-blog.example.com/clean-architecture'),
                            new OA\Property(property: 'is_featured', type: 'boolean', example: true),
                            new OA\Property(property: 'slug', type: 'string', example: 'ソフトウェアエンジニアのためのclean-architecture入門'),
                            new OA\Property(property: 'created_at', type: 'string', example: '2026.06.26'),
                        ]
                    )
                ),
            ]
        )
    )]
    #[OA\Response(response: 500, description: 'Lỗi server')]
    public function index(Request $request)
    {
        $locale = $request->query('locale', 'vi');
        $isFeatured = $request->query('featured');
        $categorySlug = $request->query('category');
        $perPage = $request->query('limit', 5);

        $categoryCounts = DB::table('wp_term_relationships')
            ->join('wp_term_taxonomy', 'wp_term_relationships.term_taxonomy_id', '=', 'wp_term_taxonomy.term_taxonomy_id')
            ->join('wp_terms', 'wp_term_taxonomy.term_id', '=', 'wp_terms.term_id')
            ->join('wp_posts', 'wp_term_relationships.object_id', '=', 'wp_posts.ID')
            ->where('wp_posts.post_type', 'news')
            ->where('wp_posts.post_status', 'publish')
            ->select('wp_terms.slug', DB::raw('count(*) as total'))
            ->groupBy('wp_terms.slug')
            ->pluck('total', 'slug');

        $query = WpPost::query()
            ->where('post_type', 'news')
            ->where('post_status', 'publish')
            ->with([
                'meta',
                'terms',
                'translations' => function ($q) use ($locale) {
                    $q->where('locale', $locale);
                },
            ]);

        if ($isFeatured) {
            $query->whereHas('meta', function ($q) {
                $q->where('meta_key', 'is_featured')->where('meta_value', 1);
            });
        }

        if ($categorySlug) {
            $query->whereHas('terms', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        $news = $query->orderBy('post_date', 'desc')->paginate($perPage);

        $formattedNews = $news->map(function ($item) {
            $meta = $item->meta->pluck('meta_value', 'meta_key');
            $translation = $item->translations->first();
            $title = $translation ? $translation->post_title : $item->post_title;

            $imageMeta = $meta['image'] ?? '';
            $finalImage = $imageMeta;
            if (is_numeric($imageMeta)) {
                $imagePost = WpPost::find($imageMeta);
                $finalImage = $imagePost ? $imagePost->guid : '';
            }

            $category = $item->terms->first();

            return [
                'id' => $item->ID,
                'title' => $title,
                'subtitle' => $meta['subtitle'] ?? '',
                'image' => $finalImage,
                'link' => $meta['link'] ?? '/',
                'is_featured' => (bool) ($meta['is_featured'] ?? false),
                'slug' => urldecode($item->post_name),
                'created_at' => Carbon::parse($item->post_date)->format('Y.m.d'),
                'category' => $category ? [
                    'id' => $category->term_id,
                    'name' => $category->name,
                    'slug' => urldecode($category->slug),
                ] : null,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $formattedNews,
            'category_counts' => $categoryCounts,
            'meta' => [
                'current_page' => $news->currentPage(),
                'last_page' => $news->lastPage(),
                'per_page' => $news->perPage(),
                'total' => $news->total(),
            ],
        ]);
    }
}
