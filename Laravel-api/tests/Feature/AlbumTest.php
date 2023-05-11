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
        // Gửi request GET đến endpoint của API
        $response = $this->get('/api/getAlbumPet');

        // Kiểm tra status code của response
        $response->assertStatus(200);

        // Kiểm tra cấu trúc JSON response
        $response->assertJsonStructure([
            'status',
            'pets' => [
                '*' => [
                    'user_id',
                    'user' => [
                        'email',
                        'name'
                    ],
                ]
            ]
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
