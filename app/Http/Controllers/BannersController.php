<?php

namespace App\Http\Controllers;

use App\Models\Wordpress\WpPost;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class BannersController extends Controller
{
    #[OA\Get(
        path: "/api/banners",
        summary: "Lấy danh sách Banners",
        tags: ["Banners"]
    )]
    #[OA\Parameter(
        name: "locale",
        description: "Mã ngôn ngữ (vi, ja, en). Mặc định là 'vi'",
        in: "header",
        required: false,
        schema: new OA\Schema(type: "string", default: "vi")
    )]
    #[OA\Response(response: 200, description: "Lấy dữ liệu thành công")]
    #[OA\Response(response: 500, description: "Lỗi server")]

    public function getBanners(Request $request)
    {
        $locale = $request->header('locale', 'vi');

        $banners = WpPost::where('post_type', 'home_banner')
            ->where('post_status', 'publish')
            ->with(['translations' => function ($query) use ($locale) {
                $query->where('locale', $locale);
            }, 'meta'])
            ->get();

        $formattedBanners = $banners->map(function ($banner) {
            $translation = $banner->translations->first();
            $meta = $banner->meta->pluck('meta_value', 'meta_key');


            $finalLink = $meta['banner_link'] ?? '/';


            $imageMeta = $meta['banner_image_url'] ?? '';
            $finalImage = $imageMeta;


            if (is_numeric($imageMeta)) {
                $imagePost = WpPost::find($imageMeta);
                $finalImage = $imagePost ? $imagePost->guid : '';
            }

            $title = $translation ? $translation->post_title : $banner->post_title;

            return [
                'id'       => $banner->ID,
                'subtitle' => $meta['banner_subtitle'] ?? '',
                'title'    => $title,
                'image'    => $finalImage,
                'link'     => $finalLink,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data'   => $formattedBanners
        ], 200);
    }
}
