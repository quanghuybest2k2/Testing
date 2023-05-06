<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testFetchProductsByCategory()
    {
        $slug = 'meo'; // category slug
        $response = $this->get('/api/fetchproducts/' . $slug);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'product_data' => [
                    'product' => [
                        'current_page',
                        'data' => [
                            '*' => [
                                'id',
                                'category_id',
                                'slug',
                                'name',
                                'description',
                                'brand',
                                'selling_price',
                                'original_price',
                                'qty',
                                'image',
                                'featured',
                                'status',
                                'count',
                                'created_at',
                                'updated_at',
                                'category' => [
                                    'id',
                                    'slug',
                                    'name',
                                    'description',
                                    'image',
                                    'status',
                                    'created_at',
                                    'updated_at',
                                ],
                            ],
                        ],
                        'first_page_url',
                        'from',
                        'last_page',
                        'last_page_url',
                        'links' => [
                            [
                                'url',
                                'label',
                                'active',
                            ],
                        ],
                        'next_page_url',
                        'path',
                        'per_page',
                        'prev_page_url',
                        'to',
                        'total',
                    ],
                    'category' => [
                        'id',
                        'slug',
                        'name',
                        'description',
                        'image',
                        'status',
                        'created_at',
                        'updated_at',
                    ],
                ],
                'pagination' => [
                    'current_page',
                    'last_page',
                    'per_page',
                    'total',
                ],
            ]);
    }
}
