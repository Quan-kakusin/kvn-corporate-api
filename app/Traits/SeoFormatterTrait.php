<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait SeoFormatterTrait
{
    /**
     * Build chuẩn cục SEO Object từ WP Post Meta
     */
    protected function buildSeoData($post, $meta, $locale = 'ja', $pathPrefix = '')
    {
        // 1. Title: Lấy Yoast, nếu không có thì lấy tiêu đề bài viết
        $seoTitle = $meta['_yoast_wpseo_title'] ?? $post->post_title;

        // 2. Description: Lấy Yoast, nếu không có thì cắt 150 ký tự từ nội dung bài viết
        $seoDesc = $meta['_yoast_wpseo_metadesc'] ?? '';
        if (empty($seoDesc) && ! empty($post->post_content)) {
            $seoDesc = mb_substr(strip_tags($post->post_content), 0, 150).'...';
        }

        // Xử lý link Canonical chuẩn
        $slug = $post->post_name === 'home' ? '' : $post->post_name;
        $canonicalPath = $pathPrefix ? '/'.trim($pathPrefix, '/').'/'.$slug : '/'.$slug;
        $canonicalUrl = rtrim(env('FRONTEND_URL', 'https://example.com'), '/').$canonicalPath;

        // 3. Image: Lấy Yoast OG, fallback sang Thumbnail, hoặc meta ảnh bài viết
        $ogImageId = $meta['_yoast_wpseo_opengraph-image-id'] ?? $meta['_thumbnail_id'] ?? null;
        $rawImage = $meta['_yoast_wpseo_opengraph-image'] ?? $meta['image'] ?? $meta['product_image'] ?? '';

        // Resolve ra URL hoàn chỉnh ngay tại đây
        $ogImageUrl = $this->resolveImageUrl($ogImageId, $rawImage);

        return [
            'title' => $seoTitle,
            'description' => $seoDesc,
            'keywords' => isset($meta['_yoast_wpseo_focuskw']) ? explode(',', $meta['_yoast_wpseo_focuskw']) : [],
            'canonical' => $canonicalUrl,
            'robots' => 'index,follow',
            'author' => 'KAKUSIN VN',
            'og' => [
                'title' => $meta['_yoast_wpseo_opengraph-title'] ?? $seoTitle,
                'description' => $meta['_yoast_wpseo_opengraph-description'] ?? $seoDesc,
                'image' => $ogImageUrl,
                'url' => $canonicalUrl,
                'type' => 'website',
                'site_name' => 'KAKUSIN VN',
                'locale' => match ($locale) {
                    'en' => 'en_US',
                    'vi' => 'vi_VN',
                    default => 'ja_JP',
                },
            ],
            'twitter' => [
                'card' => 'summary_large_image',
                'title' => $meta['_yoast_wpseo_twitter-title'] ?? $seoTitle,
                'description' => $meta['_yoast_wpseo_twitter-description'] ?? $seoDesc,
                'image' => $ogImageUrl,
            ],
            'json_ld' => [
                '@context' => 'https://schema.org',
                '@type' => 'Organization',
                'name' => 'KAKUSIN VN',
                'url' => env('FRONTEND_URL', 'https://example.com'),
                'logo' => env('FRONTEND_URL', 'https://example.com').'/logo.png',
            ],
        ];
    }

    /**
     * Xử lý lấy link ảnh thật từ ID (number) hoặc URL (string)
     */
    protected function resolveImageUrl($imageId, $fallbackUrl = '')
    {
        // Nếu là ID số, truy vấn DB để lấy guid (URL)
        if (is_numeric($imageId) && $imageId > 0) {
            $attachment = DB::table('wp_posts')
                ->where('ID', $imageId)
                ->where('post_type', 'attachment')
                ->first();

            return $attachment ? $attachment->guid : $fallbackUrl;
        }

        // Nếu đã là URL rồi thì trả về luôn
        return ! empty($imageId) ? $imageId : $fallbackUrl;
    }
}
