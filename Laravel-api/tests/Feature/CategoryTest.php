<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public $email = 'admin@gmail.com';
    public $password = '12345678';

    // Xem danh mục
    public function testGetAllCategory()
    {
        $response = $this->get('/api/getCategory');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'category' => [
                '*' => [
                    'id',
                    'name',
                    'status',
                    'created_at',
                    'updated_at',
                ]
            ]
        ]);
    }
    // Thêm danh mục
    public function testCreateCategory()
    {
        // Gửi yêu cầu đăng nhập và lấy token
        $response = $this->json('POST', '/api/login', [
            'email' => $this->email,
            'password' => $this->password,
        ]);

        $token = $response->json('token');

        $data = [
            'slug' => 'abc',
            'name' => 'ABC',
            'description' => 'ABC Việt Nam',
            'status' => '0',
            'image' => UploadedFile::fake()->image('ca.png'),
        ];

        // Kiểm tra nếu email đã tồn tại
        if (Category::where('slug', $data['slug'])->exists()) {
            $this->assertTrue(false, 'Đã tồn tại slug ' . $data['slug']);
        }
        // Xác thực
        $response = $this->withHeader('Authorization', "Bearer $token")->json('POST', '/api/store-category', $data);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 200,
            'message' => 'Thêm danh mục thành công.'
        ]);
    }

    // xóa category
    public function testDestroyCategory()
    {
        $id = 4;
        // Gửi yêu cầu đăng nhập và lấy token
        $response = $this->json('POST', '/api/login', [
            'email' => $this->email,
            'password' => $this->password,
        ]);

        $token = $response->json('token');

        $category = Category::find($id);

        if (!$category) {
            $this->assertTrue(false, 'Không tìm thấy id của danh mục!');
        }
        // Xác thực
        $response = $this->withHeader('Authorization', "Bearer $token")->json('DELETE', '/api/delete-category/' . $category->id);

        // Kiểm tra xem response có trả về 200 và message là 'Đã xóa danh mục.'
        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Đã xóa danh mục.'
            ]);

        // Kiểm tra xem category đã bị xóa trong database
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
    // ...
}
