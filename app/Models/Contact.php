<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    title: 'Contact',
    description: 'Thông tin liên hệ từ khách hàng',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'category', type: 'string', example: 'Tư vấn dịch vụ'),
        new OA\Property(property: 'name', type: 'string', example: 'Trần Anh Quân'),
        new OA\Property(property: 'company', type: 'string', example: 'Kakusin'),
        new OA\Property(property: 'email', type: 'string', example: 'quan@example.com'),
        new OA\Property(property: 'phone', type: 'string', example: '0901234567', nullable: true),
        new OA\Property(property: 'content', type: 'string', example: 'Tôi cần tư vấn chi tiết...'),
        new OA\Property(property: 'agree', type: 'boolean', example: true),
        new OA\Property(property: 'is_read', type: 'boolean', example: false),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
    ]
)]
class Contact extends Model
{
    protected $fillable = [
        'category',
        'name',
        'company',
        'email',
        'phone',
        'content',
        'agree',
        'is_read',
    ];
}
