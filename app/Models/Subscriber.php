<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Subscriber',
    title: 'Subscriber',
    description: 'Model đăng ký nhận bản tin'
)]
class Subscriber extends Model
{
    #[OA\Property(property: 'id', type: 'integer', example: 1)]
    #[OA\Property(property: 'email', type: 'string', format: 'email', example: 'user@example.com')]
    #[OA\Property(property: 'is_active', type: 'boolean', example: true)]
    protected $fillable = ['email', 'is_active'];
}
