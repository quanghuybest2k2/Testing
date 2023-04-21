<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AlbumTest extends TestCase
{
    /**
     * A basic feature test example.
     */
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
    public function test_CreatAlbum()
    {
        $user = \App\Models\User::where('email', 'quanghuybest@gmail.com')->firstOrFail();
        $this->actingAs($user);

        $data = [
            'user_id' => '1',
            'category_id' => '2',
            'emotion' => 'Khỉ dễ thương',
            'image_pet' => UploadedFile::fake()->image('khi.jpg'),
        ];

        $response = $this->json('POST', '/api/store-albumPet', $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Thêm album thành công.',
            ]);
    }
}
