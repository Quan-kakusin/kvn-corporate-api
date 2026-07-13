<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class SeoController extends Controller
{
    #[OA\Get(path: '/api/seo/pages', summary: 'Lấy cấu hình SEO đa ngôn ngữ của trang tĩnh', tags: ['SEO'])]
    #[OA\Parameter(name: 'locale', in: 'query', schema: new OA\Schema(type: 'string', default: 'ja', enum: ['ja', 'vi', 'en']))]
    #[OA\Response(response: 200, description: 'Lấy dữ liệu SEO thành công')]
    public function getStaticPages(Request $request)
    {
        $baseUrl = rtrim(env('FRONTEND_URL', 'https://example.com'), '/');

        $locale = $request->query('locale', 'ja');
        if (!in_array($locale, ['ja', 'vi', 'en'])) {
            $locale = 'ja';
        }

        $ogLocaleMap = [
            'ja' => 'ja_JP',
            'vi' => 'vi_VN',
            'en' => 'en_US',
        ];
        $ogLocale = $ogLocaleMap[$locale];

        $data = [
            'home' => [
                'id' => 1,
                'slug' => '/',
                'title' => ['ja' => 'ホーム', 'vi' => 'Trang chủ', 'en' => 'Home'][$locale],
                'content' => '',
                'seo' => [
                    'title' => [
                        'ja' => 'ホーム | KAKUSIN VIETNAM',
                        'vi' => 'Trang chủ | KAKUSIN VIETNAM',
                        'en' => 'Home | KAKUSIN VIETNAM'
                    ][$locale],
                    'description' => [
                        'ja' => '私たちは、野心的なアイデアを、成長する企業のための明確で有用かつスケーラブルなデジタル体験に変えます。',
                        'vi' => 'Chúng tôi biến những ý tưởng tham vọng thành các trải nghiệm kỹ thuật số rõ ràng, hữu ích và có khả năng mở rộng cho doanh nghiệp.',
                        'en' => 'We turn ambitious ideas into clear, useful and scalable digital experiences for growing businesses.'
                    ][$locale],
                    'keywords' => ['kakusin vietnam', 'digital experiences', 'technology', 'innovation'],
                    'canonical' => $baseUrl . '/',
                    'robots' => 'index,follow',
                    'author' => 'KAKUSIN VIETNAM',
                    'og' => [
                        'title' => ['ja' => 'ホーム | KAKUSIN VIETNAM', 'vi' => 'Trang chủ | KAKUSIN VIETNAM', 'en' => 'Home | KAKUSIN VIETNAM'][$locale],
                        'description' => ['ja' => 'スケーラブルなデジタル体験', 'vi' => 'Trải nghiệm kỹ thuật số', 'en' => 'Scalable digital experiences'][$locale],
                        'image' => $baseUrl . '/images/home-banner.jpg',
                        'url' => $baseUrl . '/',
                        'type' => 'website',
                        'site_name' => 'KAKUSIN VIETNAM',
                        'locale' => $ogLocale
                    ],
                    'twitter' => [
                        'card' => 'summary_large_image',
                        'title' => ['ja' => 'ホーム | KAKUSIN VIETNAM', 'vi' => 'Trang chủ | KAKUSIN VIETNAM', 'en' => 'Home | KAKUSIN VIETNAM'][$locale],
                        'description' => ['ja' => 'スケーラブルなデジタル体験', 'vi' => 'Trải nghiệm kỹ thuật số', 'en' => 'Scalable digital experiences'][$locale],
                        'image' => $baseUrl . '/images/home-banner.jpg'
                    ],
                    'json_ld' => [
                        '@context' => 'https://schema.org',
                        '@type' => 'Organization',
                        'name' => 'KAKUSIN VIETNAM Co., Ltd.',
                        'url' => $baseUrl,
                        'logo' => $baseUrl . '/logo.png'
                    ]
                ]
            ],

            'about-us' => [
                'id' => 2,
                'slug' => 'about-us',
                'title' => ['ja' => '会社概要', 'vi' => 'Về chúng tôi', 'en' => 'About Us'][$locale],
                'content' => '',
                'seo' => [
                    'title' => [
                        'ja' => '会社概要 | KAKUSIN VIETNAM',
                        'vi' => 'Về chúng tôi | KAKUSIN VIETNAM',
                        'en' => 'About Us | KAKUSIN VIETNAM'
                    ][$locale],
                    'description' => [
                        'ja' => 'KAKUSIN VIETNAMは、テクノロジーとクリエイティビティの融合を通じて社会に新しい価値を創造するデジタルスタジオです。',
                        'vi' => 'KAKUSIN VIETNAM là một studio kỹ thuật số tạo ra giá trị mới cho xã hội thông qua sự kết hợp giữa công nghệ và sáng tạo.',
                        'en' => 'KAKUSIN VIETNAM is a digital studio that creates new value for society through the fusion of technology and creativity.'
                    ][$locale],
                    'keywords' => ['digital studio', 'UX Design', 'AI Solution Construction', 'Japan-quality'],
                    'canonical' => $baseUrl . '/about-us',
                    'robots' => 'index,follow',
                    'author' => 'KAKUSIN VIETNAM',
                    'og' => [
                        'title' => ['ja' => '会社概要 | KAKUSIN VIETNAM', 'vi' => 'Về chúng tôi | KAKUSIN VIETNAM', 'en' => 'About Us | KAKUSIN VIETNAM'][$locale],
                        'description' => ['ja' => 'テクノロジーとクリエイティビティの融合', 'vi' => 'Sự kết hợp giữa công nghệ và sáng tạo', 'en' => 'Fusion of technology and creativity'][$locale],
                        'image' => $baseUrl . '/images/about-banner.jpg',
                        'url' => $baseUrl . '/about-us',
                        'type' => 'website',
                        'site_name' => 'KAKUSIN VIETNAM',
                        'locale' => $ogLocale
                    ],
                    'twitter' => [
                        'card' => 'summary_large_image',
                        'title' => ['ja' => '会社概要 | KAKUSIN VIETNAM', 'vi' => 'Về chúng tôi | KAKUSIN VIETNAM', 'en' => 'About Us | KAKUSIN VIETNAM'][$locale],
                        'description' => ['ja' => 'テクノロジーとクリエイティビティの融合', 'vi' => 'Sự kết hợp giữa công nghệ và sáng tạo', 'en' => 'Fusion of technology and creativity'][$locale],
                        'image' => $baseUrl . '/images/about-banner.jpg'
                    ],
                    'json_ld' => [
                        '@context' => 'https://schema.org',
                        '@type' => 'Organization',
                        'name' => 'KAKUSIN VIETNAM Co., Ltd.',
                        'url' => $baseUrl,
                        'logo' => $baseUrl . '/logo.png'
                    ]
                ]
            ],

            'services' => [
                'id' => 4,
                'slug' => 'services',
                'title' => ['ja' => 'サービス', 'vi' => 'Dịch vụ', 'en' => 'Services'][$locale],
                'content' => '',
                'seo' => [
                    'title' => [
                        'ja' => 'サービス | KAKUSIN VIETNAM',
                        'vi' => 'Dịch vụ | KAKUSIN VIETNAM',
                        'en' => 'Services | KAKUSIN VIETNAM'
                    ][$locale],
                    'description' => [
                        'ja' => '拡張性の高いWeb開発、モバイルアプリ開発、クラウドインフラソリューションを日本品質の精度で提供します。',
                        'vi' => 'Cung cấp các giải pháp phát triển web, ứng dụng di động và hạ tầng đám mây có khả năng mở rộng với độ chính xác chuẩn Nhật Bản.',
                        'en' => 'Providing scalable web development, mobile app development, and cloud infrastructure solutions with Japan-quality precision.'
                    ][$locale],
                    'keywords' => ['Web Development', 'App Development', 'Cloud Infrastructure', 'DevOps'],
                    'canonical' => $baseUrl . '/services',
                    'robots' => 'index,follow',
                    'author' => 'KAKUSIN VIETNAM',
                    'og' => [
                        'title' => ['ja' => 'サービス | KAKUSIN VIETNAM', 'vi' => 'Dịch vụ | KAKUSIN VIETNAM', 'en' => 'Services | KAKUSIN VIETNAM'][$locale],
                        'description' => ['ja' => '日本基準の品質管理', 'vi' => 'Chất lượng chuẩn Nhật Bản', 'en' => 'Japan-quality precision'][$locale],
                        'image' => $baseUrl . '/images/services-banner.jpg',
                        'url' => $baseUrl . '/services',
                        'type' => 'website',
                        'site_name' => 'KAKUSIN VIETNAM',
                        'locale' => $ogLocale
                    ],
                    'twitter' => [
                        'card' => 'summary_large_image',
                        'title' => ['ja' => 'サービス | KAKUSIN VIETNAM', 'vi' => 'Dịch vụ | KAKUSIN VIETNAM', 'en' => 'Services | KAKUSIN VIETNAM'][$locale],
                        'description' => ['ja' => '日本基準の品質管理', 'vi' => 'Chất lượng chuẩn Nhật Bản', 'en' => 'Japan-quality precision'][$locale],
                        'image' => $baseUrl . '/images/services-banner.jpg'
                    ],
                    'json_ld' => [
                        '@context' => 'https://schema.org',
                        '@type' => 'Organization',
                        'name' => 'KAKUSIN VIETNAM Co., Ltd.',
                        'url' => $baseUrl,
                        'logo' => $baseUrl . '/logo.png'
                    ]
                ]
            ],

            'news' => [
                'id' => 5,
                'slug' => 'news',
                'title' => ['ja' => 'ニュース', 'vi' => 'Tin tức', 'en' => 'News'][$locale],
                'content' => '',
                'seo' => [
                    'title' => [
                        'ja' => 'ニュース | KAKUSIN VIETNAM',
                        'vi' => 'Tin tức | KAKUSIN VIETNAM',
                        'en' => 'News | KAKUSIN VIETNAM'
                    ][$locale],
                    'description' => [
                        'ja' => 'KAKUSIN VIETNAMからの最新情報、プレスリリース、イベント情報をご覧ください。',
                        'vi' => 'Đọc các thông tin cập nhật, thông cáo báo chí và sự kiện mới nhất từ KAKUSIN VIETNAM.',
                        'en' => 'Read the latest updates, press releases, and event information from KAKUSIN VIETNAM.'
                    ][$locale],
                    'keywords' => ['Press Release', 'Event', 'Insight', 'kakusin news'],
                    'canonical' => $baseUrl . '/news',
                    'robots' => 'index,follow',
                    'author' => 'KAKUSIN VIETNAM',
                    'og' => [
                        'title' => ['ja' => 'ニュース | KAKUSIN VIETNAM', 'vi' => 'Tin tức | KAKUSIN VIETNAM', 'en' => 'News | KAKUSIN VIETNAM'][$locale],
                        'description' => ['ja' => '最新ニュースとイベント', 'vi' => 'Tin tức và sự kiện mới nhất', 'en' => 'Latest news and events'][$locale],
                        'image' => $baseUrl . '/images/news-banner.jpg',
                        'url' => $baseUrl . '/news',
                        'type' => 'website',
                        'site_name' => 'KAKUSIN VIETNAM',
                        'locale' => $ogLocale
                    ],
                    'twitter' => [
                        'card' => 'summary_large_image',
                        'title' => ['ja' => 'ニュース | KAKUSIN VIETNAM', 'vi' => 'Tin tức | KAKUSIN VIETNAM', 'en' => 'News | KAKUSIN VIETNAM'][$locale],
                        'description' => ['ja' => '最新ニュースとイベント', 'vi' => 'Tin tức và sự kiện mới nhất', 'en' => 'Latest news and events'][$locale],
                        'image' => $baseUrl . '/images/news-banner.jpg'
                    ],
                    'json_ld' => [
                        '@context' => 'https://schema.org',
                        '@type' => 'Organization',
                        'name' => 'KAKUSIN VIETNAM Co., Ltd.',
                        'url' => $baseUrl,
                        'logo' => $baseUrl . '/logo.png'
                    ]
                ]
            ],

            'products' => [
                'id' => 6,
                'slug' => 'products',
                'title' => ['ja' => '製品と実績', 'vi' => 'Dự án & Sản phẩm', 'en' => 'Works & Products'][$locale],
                'content' => '',
                'seo' => [
                    'title' => [
                        'ja' => '製品と実績 | KAKUSIN VIETNAM',
                        'vi' => 'Dự án & Sản phẩm | KAKUSIN VIETNAM',
                        'en' => 'Works & Products | KAKUSIN VIETNAM'
                    ][$locale],
                    'description' => [
                        'ja' => 'Web、アプリ、システム開発における200以上の成功プロジェクトの実績をご覧ください。',
                        'vi' => 'Khám phá danh mục hơn 200 dự án thành công của chúng tôi trong lĩnh vực phát triển web, ứng dụng và hệ thống.',
                        'en' => 'Explore our portfolio of over 200 successful projects in web, app, and system development.'
                    ][$locale],
                    'keywords' => ['Web Development', 'App Development', 'System Architecture', 'Portfolio'],
                    'canonical' => $baseUrl . '/products',
                    'robots' => 'index,follow',
                    'author' => 'KAKUSIN VIETNAM',
                    'og' => [
                        'title' => ['ja' => '製品と実績 | KAKUSIN VIETNAM', 'vi' => 'Dự án & Sản phẩm | KAKUSIN VIETNAM', 'en' => 'Works & Products | KAKUSIN VIETNAM'][$locale],
                        'description' => ['ja' => 'プロジェクト実績', 'vi' => 'Thành tựu dự án', 'en' => 'Project Portfolio'][$locale],
                        'image' => $baseUrl . '/images/products-banner.jpg',
                        'url' => $baseUrl . '/products',
                        'type' => 'website',
                        'site_name' => 'KAKUSIN VIETNAM',
                        'locale' => $ogLocale
                    ],
                    'twitter' => [
                        'card' => 'summary_large_image',
                        'title' => ['ja' => '製品と実績 | KAKUSIN VIETNAM', 'vi' => 'Dự án & Sản phẩm | KAKUSIN VIETNAM', 'en' => 'Works & Products | KAKUSIN VIETNAM'][$locale],
                        'description' => ['ja' => 'プロジェクト実績', 'vi' => 'Thành tựu dự án', 'en' => 'Project Portfolio'][$locale],
                        'image' => $baseUrl . '/images/products-banner.jpg'
                    ],
                    'json_ld' => [
                        '@context' => 'https://schema.org',
                        '@type' => 'Organization',
                        'name' => 'KAKUSIN VIETNAM Co., Ltd.',
                        'url' => $baseUrl,
                        'logo' => $baseUrl . '/logo.png'
                    ]
                ]
            ],
        ];

        return response()->json([
            'success' => true,
            'data' => $data
        ], 200);
    }
}
