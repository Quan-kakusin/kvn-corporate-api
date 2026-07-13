<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Mail\SubscriptionSuccess;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail; // Bổ sung thư viện Mail
use OpenApi\Attributes as OA; // Bổ sung Mailable vừa tạo

class SubscriberController extends Controller
{
    #[OA\Post(
        path: '/api/subscriber',
        summary: 'Đăng ký nhận bản tin (Newsletter)',
        tags: ['Subscriber']
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'email', type: 'string', format: 'email', example: 'user@example.com'),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Đăng ký thành công',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'You have successfully subscribed to our newsletter.'),
            ]
        )
    )]
    #[OA\Response(
        response: 422,
        description: 'Dữ liệu không hợp lệ (email đã tồn tại hoặc sai định dạng)',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'This email is already subscribed.'),
                new OA\Property(
                    property: 'errors',
                    type: 'object',
                    properties: [
                        new OA\Property(
                            property: 'email',
                            type: 'array',
                            items: new OA\Items(type: 'string', example: 'This email is already subscribed.')
                        ),
                    ]
                ),
            ]
        )
    )]
    public function store(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email|unique:subscribers,email',
            ],
            ['email.unique' => 'This email is already subscribed.']
        );

        // 1. Lưu vào Database
        Subscriber::create([
            'email' => $request->email,
            'is_active' => true,
        ]);

        // 2. Gửi email thông báo đăng ký thành công
        Mail::to($request->email)->send(new SubscriptionSuccess);

        return response()->json(['status' => 'success', 'message' => 'You have successfully subscribed to our newsletter.']);
    }
}
