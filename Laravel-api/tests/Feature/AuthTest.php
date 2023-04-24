<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use WithFaker;
    /**
     * A basic feature test example.
     */
    public $name = 'Admin';
    public $email = 'admin@gmail.com';
    public $password = '12345678';

    public function testRegister()
    {
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ];
        // Kiểm tra nếu email đã tồn tại
        if (User::where('email', $this->email)->exists()) {
            $this->assertTrue(false, 'Đã tồn tại user');
        }

        $response = $this->json('POST', '/api/register', $data);
        if (!$response) {
            $response->assertStatus(400);
        }
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'status',
            'username',
            'token',
            'message',
        ]);
        $this->assertDatabaseHas('users', [
            'name' => $data['name'],
            'email' => $data['email'],
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

    public function test_logout()
    {
        $user = User::where('email', '=', $this->email)->first();

        // Đăng nhập vào hệ thống và lấy Auth Token
        $token = $user->createToken('TestToken')->plainTextToken;

        // Gửi yêu cầu đăng xuất với Auth Token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');

        // Kiểm tra kết quả trả về của yêu cầu đăng xuất
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
            ])
            ->assertJson([
                'status' => 200,
                'message' => 'Đã đăng xuất.',
            ]);

        // Kiểm tra Auth Token đã bị xóa khỏi cơ sở dữ liệu
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'name' => $this->name,
        ]);
    }
}
