<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    title: 'Contact',
    description: 'Thông tin liên hệ từ khách hàng',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Trần Anh Quân'),
        new OA\Property(property: 'email', type: 'string', example: 'quan@example.com'),
        new OA\Property(property: 'phone', type: 'string', example: '0901234567'),
        new OA\Property(property: 'message', type: 'string', example: 'Tôi cần tư vấn...'),
        new OA\Property(property: 'is_read', type: 'boolean', example: false),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
    ]
)]
class Contact extends Model
{
    //
}
