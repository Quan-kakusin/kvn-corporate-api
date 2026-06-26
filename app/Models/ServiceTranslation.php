<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    title: 'ServiceTranslation',
    description: 'Dữ liệu đa ngôn ngữ của Dịch vụ',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'service_id', type: 'integer', example: 5),
        new OA\Property(property: 'locale', type: 'string', example: 'en'),
        new OA\Property(property: 'name', type: 'string', example: 'Web Development'),
        new OA\Property(property: 'short_description', type: 'string', example: 'Mô tả ngắn gọn...'),
        new OA\Property(property: 'content', type: 'string', example: 'Nội dung bài viết chi tiết...'),
    ]
)]
class ServiceTranslation extends Model
{
    //
}
