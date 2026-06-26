<?php

namespace App\Models\Wordpress;

use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    title: "WpPostMeta",
    description: "Model lưu dữ liệu ACF và Meta của WordPress",
    properties: [
        new OA\Property(property: "meta_id", type: "integer", example: 100),
        new OA\Property(property: "post_id", type: "integer", example: 9),
        new OA\Property(property: "meta_key", type: "string", example: "banner_subtitle"),
        new OA\Property(property: "meta_value", type: "string", example: "INNOVATION THROUGH DIGITAL"),
    ]
)]
class WpPostMeta extends Model
{
    protected $table = 'wp_postmeta';
    protected $primaryKey = 'meta_id';
    public $timestamps = false;
}
