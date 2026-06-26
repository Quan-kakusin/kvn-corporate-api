<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string $message
 * @property int $is_read
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereIsRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereUpdatedAt($value)
 */
	class Contact extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $image_url
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUpdatedAt($value)
 */
	class Product extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $product_id
 * @property string $locale
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTranslation whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTranslation whereUpdatedAt($value)
 */
	class ProductTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $icon_url
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereIconUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereUpdatedAt($value)
 */
	class Service extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $service_id
 * @property string $locale
 * @property string $name
 * @property string|null $short_description
 * @property string|null $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceTranslation whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceTranslation whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceTranslation whereShortDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceTranslation whereUpdatedAt($value)
 */
	class ServiceTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models\Wordpress{
/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @property int $ID
 * @property int $post_author
 * @property string $post_date
 * @property string $post_date_gmt
 * @property string $post_content
 * @property string $post_title
 * @property string $post_excerpt
 * @property string $post_status
 * @property string $comment_status
 * @property string $ping_status
 * @property string $post_password
 * @property string $post_name
 * @property string $to_ping
 * @property string $pinged
 * @property string $post_modified
 * @property string $post_modified_gmt
 * @property string $post_content_filtered
 * @property int $post_parent
 * @property string $guid
 * @property int $menu_order
 * @property string $post_type
 * @property string $post_mime_type
 * @property int $comment_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Wordpress\WpPostMeta> $meta
 * @property-read int|null $meta_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Wordpress\WpPostTranslation> $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPost newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPost newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPost query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPost whereCommentCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPost whereCommentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPost whereGuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPost whereID($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPost whereMenuOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPost wherePingStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPost wherePinged($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPost wherePostAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPost wherePostContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPost wherePostContentFiltered($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPost wherePostDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPost wherePostDateGmt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPost wherePostExcerpt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPost wherePostMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPost wherePostModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPost wherePostModifiedGmt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPost wherePostName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPost wherePostParent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPost wherePostPassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPost wherePostStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPost wherePostTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPost wherePostType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPost whereToPing($value)
 */
	class WpPost extends \Eloquent {}
}

namespace App\Models\Wordpress{
/**
 * @property int $meta_id
 * @property int $post_id
 * @property string|null $meta_key
 * @property string|null $meta_value
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPostMeta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPostMeta newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPostMeta query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPostMeta whereMetaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPostMeta whereMetaKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPostMeta whereMetaValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPostMeta wherePostId($value)
 */
	class WpPostMeta extends \Eloquent {}
}

namespace App\Models\Wordpress{
/**
 * @property int $id
 * @property int $post_id
 * @property string $locale
 * @property string|null $post_title
 * @property string|null $post_content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPostTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPostTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPostTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPostTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPostTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPostTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPostTranslation wherePostContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPostTranslation wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPostTranslation wherePostTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WpPostTranslation whereUpdatedAt($value)
 */
	class WpPostTranslation extends \Eloquent {}
}

