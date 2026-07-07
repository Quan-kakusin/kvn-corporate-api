<?php

namespace App\Http\Controllers;

use App\Models\Wordpress\WpPost;
use Carbon\Carbon;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ServiceController extends Controller
{
    #[OA\Get(path: '/api/services', summary: 'Danh sách Services', tags: ['Services'])]
    #[OA\Parameter(name: 'locale', in: 'query', schema: new OA\Schema(type: 'string', default: 'ja'))]
    #[OA\Response(response: 200, description: 'Lấy dữ liệu thành công')]
    public function index(Request $request)
    {
        $locale = $request->query('locale', 'ja');

        $services = WpPost::query()
            ->where('post_type', 'service')
            ->where('post_status', 'publish')
            ->with(['meta'])
            ->orderBy('post_date', 'asc')
            ->get();

        $formattedServices = $services->map(function ($item) use ($locale) {
            $meta = $item->meta->pluck('meta_value', 'meta_key');

            $imageUrl = $meta['service_image'] ?? '';

            $title    = $meta['title_' . $locale] ?? $meta['title_ja'] ?? $item->post_title;
            $subtitle = $meta['subtitle_' . $locale] ?? $meta['subtitle_ja'] ?? '';
            $content  = $meta['content_' . $locale] ?? $meta['content_ja'] ?? $item->post_content;

            return [
                'id'          => $item->ID,
                'title'       => $title,
                'subtitle'    => $subtitle,
                'description' => $content,
                'image'       => [
                    'url' => $imageUrl
                ],
                'created_at'  => Carbon::parse($item->post_date)->format('Y.m.d'),
            ];
        });

        return response()->json([
            'status' => 'success',
            'data'   => $formattedServices
        ]);
    }
}
