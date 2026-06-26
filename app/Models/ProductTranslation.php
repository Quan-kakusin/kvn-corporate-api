<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    title: "ProductTranslation",
    description: "Dữ liệu đa ngôn ngữ của Sản phẩm",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "product_id", type: "integer", example: 10),
        new OA\Property(property: "locale", type: "string", example: "vi"),
        new OA\Property(property: "name", type: "string", example: "Tên sản phẩm việt hóa"),
        new OA\Property(property: "description", type: "string", example: "Mô tả chi tiết..."),
    ]
)]
class ProductTranslation extends Model
{
    //
}
