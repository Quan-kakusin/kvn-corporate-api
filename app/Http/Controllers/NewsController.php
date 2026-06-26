<?php

namespace App\Http\Controllers;

use App\Models\Wordpress\WpPost;
use Illuminate\Http\Request;
use Carbon\Carbon;
use OpenApi\Attributes as OA;

class NewsController extends Controller
{
    #[OA\Get(
        path: "/api/news",
        summary: "Lấy danh sách tin tức (News)",
        tags: ["News"]
    )]
    #[OA\Parameter(
        name: "locale",
        description: "Mã ngôn ngữ (vi, ja, en). Mặc định là 'vi'",
        in: "query",
        required: false,
        schema: new OA\Schema(type: "string", default: "vi")
    )]
    #[OA\Parameter(
        name: "featured",
        description: "Lọc theo bài viết nổi bật (truyền true để lọc)",
        in: "query",
        required: false,
        schema: new OA\Schema(type: "boolean")
    )]
    #[OA\Parameter(
        name: "category",
        description: "Lọc theo danh mục (slug: press-release, event, insight)",
        in: "query",
        required: false,
        schema: new OA\Schema(type: "string")
    )]
    #[OA\Response(
        response: 200,
        description: "Lấy dữ liệu thành công",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "status", type: "string", example: "success"),
                new OA\Property(
                    property: "data",
                    type: "array",
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: "id", type: "integer", example: 21),
                            new OA\Property(property: "title", type: "string", example: "ソフトウェアエンジニアのためのClean Architecture入門"),
                            new OA\Property(property: "subtitle", type: "string", example: "MASTERING PROFESSIONAL DEVELOPMENT"),
                            new OA\Property(property: "image", type: "string", example: "http://localhost:8080/wp-content/uploads/2026/06/1-1.jpg"),
                            new OA\Property(property: "link", type: "string", example: "https://dev-blog.example.com/clean-architecture"),
                            new OA\Property(property: "is_featured", type: "boolean", example: true),
                            new OA\Property(property: "slug", type: "string", example: "ソフトウェアエンジニアのためのclean-architecture入門"),
                            new OA\Property(property: "created_at", type: "string", example: "2026.06.26")
                        ]
                    )
                )
            ]
        )
    )]
    #[OA\Response(response: 500, description: "Lỗi server")]

    public function index(Request $request)
    {

        $locale = $request->query('locale', 'vi');
        $isFeatured = $request->query('featured');
        $categorySlug = $request->query('category');

        $query = WpPost::query()
            ->where('post_type', 'news')
            ->where('post_status', 'publish')
            ->with([
                'meta',
                'translations' => function ($q) use ($locale) {
                    $q->where('locale', $locale);
                }
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


        $news = $query->orderBy('post_date', 'desc')->get();

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

            return [
                'id'          => $item->ID,
                'title'       => $title,
                'subtitle'    => $meta['subtitle'] ?? '',
                'image'       => $finalImage,
                'link'        => $meta['link'] ?? '/',
                'is_featured' => (bool)($meta['is_featured'] ?? false),
                'slug'        => urldecode($item->post_name),
                'created_at'  => Carbon::parse($item->post_date)->format('Y.m.d'),
            ];
        });

        return response()->json([
            'status' => 'success',
            'data'   => $formattedNews
        ]);
    }
}
