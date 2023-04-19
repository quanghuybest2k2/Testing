<?php

namespace Tests\Feature;

use App\Models\Category;
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
        $data = [
            'slug' => 'khi',
            'name' => 'Khỉ',
            'description' => 'Khỉ Việt Nam',
            'image' => UploadedFile::fake()->image('../imgs/khi.jpg'),
            'status' => 0,
        ];

        $response = $this->json('POST', '/api/store-category', $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Thêm danh mục thành công.',
            ])->assertHeader('Content-Type', 'application/json');
    }
    // xóa category
    // public function testDestroy()
    // {
    //     // Tạo một category mới sử dụng hàm factory()
    //     $category = Category::factory(Category::class)->make();

    //     // Gửi yêu cầu DELETE đến API để xóa category với id của category mới tạo
    //     $response = $this->delete('/api/delete-category/' . $category->id);

    //     // Kiểm tra xem API đã trả về mã trạng thái 200 hay không
    //     $response->assertStatus(200);

    //     // Kiểm tra xem API đã trả về thông báo thành công tương ứng hay không
    //     $response->assertJson([
    //         'status' => 200,
    //         'message' => 'Đã xóa danh mục.'
    //     ]);

    //     // Kiểm tra xem category đã bị xóa khỏi cơ sở dữ liệu hay không
    //     $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    // }
    // ...
}
