<?php

namespace App\Models\Wordpress;

use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    title: "WpPostTranslation",
    description: "Model lưu dữ liệu đa ngôn ngữ cho các bài viết WordPress",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "post_id", type: "integer", example: 9),
        new OA\Property(property: "locale", type: "string", example: "ja"),
        new OA\Property(property: "post_title", type: "string", example: "デジタルで、ビジネスに革新を。"),
        new OA\Property(property: "post_content", type: "string", example: "Nội dung tiếng Nhật..."),
    ]
)]
class WpPostTranslation extends Model
{
    protected $table = 'wp_post_translations';
    protected $fillable = [
        'post_id',
        'locale',
        'post_title',
        'post_content'
    ];
}
