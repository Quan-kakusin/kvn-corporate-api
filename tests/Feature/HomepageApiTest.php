<?php

namespace Tests\Feature;

use Tests\TestCase;

// use Illuminate\Foundation\Testing\RefreshDatabase;

class HomepageApiTest extends TestCase
{
    /**
     * API: GET /api/banners
     */
    public function test_get_homepage_banners_success()
    {
        $response = $this->withHeaders([
            'locale' => 'ja',
        ])->getJson('/api/banners');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => [
                        'id',
                        'subtitle',
                        'title',
                        'image',
                        'link',
                    ],
                ],
            ]);
    }

    /**
     * API: GET /api/news
     */
    public function test_get_homepage_news_success_with_default_params()
    {
        $response = $this->getJson('/api/news?locale=ja');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'subtitle',
                        'image',
                        'link',
                        'is_featured',
                        'slug',
                        'created_at',
                        'category',
                    ],
                ],
                'category_counts',
                'meta' => [
                    'current_page',
                    'last_page',
                    'per_page',
                    'total',
                ],
            ]);
    }

    public function test_get_homepage_news_with_filters()
    {

        $response = $this->getJson('/api/news?locale=vi&featured=true&category=insight&limit=2');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $responseData = $response->json();
        $this->assertEquals(2, $responseData['meta']['per_page']);

        if (! empty($responseData['data'])) {
            foreach ($responseData['data'] as $newsItem) {
                $this->assertTrue($newsItem['is_featured']);

                $this->assertEquals('insight', $newsItem['category']['slug']);
            }
        }
    }
}
