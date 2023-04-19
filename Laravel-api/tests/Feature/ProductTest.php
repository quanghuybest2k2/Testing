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
    public function test_GetAllPet()
    {
        $slug = 'meo';
        $response = $this->get('/api/fetchproducts/' . $slug);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'product_data' => [
                    'product' => [
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
            ]);
    }
}
