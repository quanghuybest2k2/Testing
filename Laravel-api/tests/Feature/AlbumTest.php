<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Album;
use App\Models\Category;
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AlbumTest extends TestCase
{
    public function test_GetAllPet()
    {
        $response = $this->get('/api/getAlbumPet');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'pets' => [
                    '*' => [
                        'id',
                        'user_id',
                        'category_id',
                        'emotion',
                        'image_pet',
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
                        'user' => [
                            'id',
                            'name',
                            'email',
                            'email_verified_at',
                            'role_as',
                            'created_at',
                            'updated_at',
                        ],
                    ],
                ],
            ]);
    }

    public function testStoreWithoutAuthentication()
    {
        $response = $this->postJson('/api/store-albumPet', [
            'category_id' => 1,
            'emotion' => 'happy',
            'image_pet' => UploadedFile::fake()->image('pet.jpg'),
        ], [
            'Authorization' => 'Bearer invalid_token'
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Bạn phải đăng nhập!']);
    }
}
