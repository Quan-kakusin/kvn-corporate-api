<?php

namespace App\Models\Wordpress;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

/**
 * @mixin Builder
 */
#[OA\Schema(
    title: 'WpPost',
    description: 'Model tương tác với bảng wp_posts của WordPress',
    properties: [
        new OA\Property(property: 'ID', type: 'integer', example: 9),
        new OA\Property(property: 'post_author', type: 'integer', example: 1),
        new OA\Property(property: 'post_date', type: 'string', format: 'date-time'),
        new OA\Property(property: 'post_title', type: 'string', example: 'Tiêu đề bài viết WP'),
        new OA\Property(property: 'post_content', type: 'string', example: 'Nội dung bài viết...'),
        new OA\Property(property: 'post_status', type: 'string', example: 'publish'),
        new OA\Property(property: 'post_type', type: 'string', example: 'home_banner'),
    ]
)]
class WpPost extends Model
{
    protected $table = 'wp_posts';

    protected $primaryKey = 'ID';


    public function meta()
    {
        return $this->hasMany(WpPostMeta::class, 'post_id', 'ID');
    }

    public function termRelationships()
    {
        return $this->hasMany(WpTermRelationship::class, 'object_id', 'ID');
    }

    public function terms()
    {
        // Mối quan hệ thông qua bảng trung gian
        return $this->belongsToMany(
            WpTerm::class,
            'wp_term_relationships',
            'object_id',
            'term_taxonomy_id'
        )->withPivot('term_taxonomy_id');
    }

    public function scopeBySlug($query, $slug)
    {
        $rawSlug = urldecode($slug);
        $wpEncodedSlug = strtolower(urlencode($rawSlug));

        return $query->whereIn('post_name', [$rawSlug, $wpEncodedSlug]);
    }
}
