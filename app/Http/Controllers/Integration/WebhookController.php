<?php

namespace App\Http\Controllers\Integration;

use App\Http\Controllers\Controller;

use App\Mail\NewPostNotification;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use OpenApi\Attributes as OA;

class WebhookController extends Controller
{
    #[OA\Post(
        path: '/api/webhook/new-post',
        summary: 'Webhook nhận thông báo bài viết mới từ WordPress',
        tags: ['Webhook']
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'title', type: 'string', example: 'Tiêu đề bài viết mới'),
                new OA\Property(property: 'link', type: 'string', example: 'https://example.com/news/article-1'),
                new OA\Property(property: 'image', type: 'string', example: 'https://example.com/image.jpg'),
                new OA\Property(property: 'subtitle', type: 'string', example: 'Mô tả ngắn gọn về bài viết'),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Gửi thông báo thành công cho danh sách subscribers',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'Notifications sent to subscribers.'),
            ]
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Lỗi server khi gửi mail',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Server Error'),
            ]
        )
    )]
    public function handleNewNews(Request $request)
    {
        $newsData = [
            'title' => $request->input('title', 'Default Title'),
            'link' => $request->input('link', '#'),
            'image' => $request->input('image', ''),
            'subtitle' => $request->input('subtitle', ''),
        ];

        $subscribers = Subscriber::where('is_active', true)->pluck('email');

        foreach ($subscribers as $email) {
            Mail::to($email)->send(new NewPostNotification($newsData));
        }

        return response()->json(['status' => 'success', 'message' => 'Notifications sent to subscribers.'], 200);
    }
}
