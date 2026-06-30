<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscriber;
use OpenApi\Attributes as OA;

class SubscriberController extends Controller
{
    #[OA\Post(
        path: '/api/subscriber', // Đã cập nhật path theo route mới
        summary: 'Đăng ký nhận bản tin (Newsletter)',
        tags: ['Subscriber']
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'email', type: 'string', format: 'email', example: 'user@example.com')
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Đăng ký thành công',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'You have successfully subscribed to our newsletter.')
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
                        )
                    ]
                )
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

        Subscriber::create([
            'email' => $request->email,
            'is_active' => true,
        ]);

        return response()->json(['status' => 'success', 'message' => 'You have successfully subscribed to our newsletter.']);
    }
}
