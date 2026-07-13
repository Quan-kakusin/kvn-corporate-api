<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

        // 1. Lấy danh sách dịch vụ
        $services = WpPost::query()
            ->where('post_type', 'service')
            ->where('post_status', 'publish')
            ->with(['meta'])
            ->orderBy('post_date', 'asc')
            ->get();

        // 2. Thu thập tất cả các ID ảnh (Attachment ID) có trong danh sách meta
        $attachmentIds = [];
        foreach ($services as $item) {
            $meta = $item->meta->pluck('meta_value', 'meta_key');
            if (! empty($meta['service_image'])) {
                $attachmentIds[] = $meta['service_image'];
            }
        }

        // 3. Query một lần duy nhất để lấy URL (guid) của tất cả các ảnh đó
        $images = [];
        if (! empty($attachmentIds)) {
            $images = WpPost::query()
                ->whereIn('ID', array_unique($attachmentIds))
                ->pluck('guid', 'ID') // Trả về dạng mảng: [139 => 'https://...', 140 => '...']
                ->toArray();
        }

        // 4. Map dữ liệu trả về giống như cũ
        $formattedServices = $services->map(function ($item) use ($locale, $images) {
            $meta = $item->meta->pluck('meta_value', 'meta_key');

            $imageId = $meta['service_image'] ?? null;
            // Nếu tìm thấy ID ảnh trong mảng $images thì lấy URL, không thì để rỗng
            $imageUrl = $images[$imageId] ?? '';

            $title = $meta['title_'.$locale] ?? $meta['title_ja'] ?? $item->post_title;
            $subtitle = $meta['subtitle_'.$locale] ?? $meta['subtitle_ja'] ?? '';
            $content = $meta['content_'.$locale] ?? $meta['content_ja'] ?? $item->post_content;

            return [
                'id' => $item->ID,
                'title' => $title,
                'subtitle' => $subtitle,
                'description' => $content,
                'image' => [
                    'url' => $imageUrl,
                ],
                'created_at' => Carbon::parse($item->post_date)->format('Y.m.d'),
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $formattedServices,
        ]);
    }
}
