<?php

namespace Tests\Feature;

use App\Models\Album;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AlbumTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public $name = 'Đoàn Quang Huy';
    public $email = 'quanghuybest@gmail.com';
    public $password = '12345678';

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

    public function test_login()
    {
        // Lấy tài khoản user được tạo sẵn trong hệ thống
        $user = User::where('email', $this->email)->first();

        // Gửi yêu cầu đăng nhập với email và password đúng
        $response = $this->postJson('/api/login', [
            'email' => $this->email,
            'password' => $this->password,
        ]);

        // Kiểm tra kết quả trả về của yêu cầu đăng nhập
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'username',
                'token',
                'message',
                'role',
            ])
            ->assertJson([
                'status' => 200,
                'username' => $user->name,
                'message' => 'Đăng nhập thành công.',
            ]);

        // Lấy Auth Token từ kết quả trả về
        $token = $response->json('token');

        // Kiểm tra Auth Token đã được tạo và trả về
        $this->assertNotEmpty($token);
    }

    // public function test_add_a_pet_to_album()
    // {
    //     $user = User::where('email', $this->email)->first();
    //     // Set up fake input data
    //     $data = [
    //         'user_id' => $user->id,
    //         'category_id' => 2,
    //         'emotion' => 'Con pet này đang test.',
    //         //lưu ảnh tạm thời ở storage/framework/testing/disks nên không cần đúng url
    //         'image_pet' => UploadedFile::fake()->image('pet.png'),
    //     ];

    //     // Call the store method and assert the response
    //     $response = $this->postJson('/api/store-albumPet', $data);
    //     if (!$response) {
    //         $response->assertStatus(400);
    //     }
    //     $response->assertStatus(200);

    //     // Assert that the pet was added to the database
    //     $this->assertDatabaseHas('albums', [
    //         'user_id' => $user->id,
    //         'category_id' => $data['category_id'],
    //         'emotion' => $data['emotion'],
    //         'image_pet' => 'uploads/album/' . $data['image_pet']->hashName(),
    //     ]);

    //     // kiểm tra xem có lưu vào thư mục /public/uploads/album hay không
    //     // nếu tồn tại thì assertExists báo lỗi tồn tại
    //     $upload_file = Storage::disk('public')->assertExists('uploads/album/' . $data['image_pet']->hashName());
    //     if (!$upload_file) {
    //         $this->assertTrue(false, 'Lỗi rồi');
    //     }
    // }
    public function test_add_a_pet_to_album()
    {
        // Gửi yêu cầu đăng nhập và lấy token
        $response = $this->json('POST', '/api/login', [
            'email' => $this->email,
            'password' => $this->password,
        ]);

        $token = $response->json('token');

        $data = [
            'user_id' => 1,
            'category_id' => 1,
            'emotion' => 'Con chim này mỏ nhọn',
            'image_pet' => UploadedFile::fake()->image('chim.png'),
        ];
        // Xác thực
        $response = $this->withHeader('Authorization', "Bearer $token")->json('POST', '/api/store-albumPet', $data);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 200,
            'message' => 'Thêm thú cưng thành công.',
        ]);
    }
}
