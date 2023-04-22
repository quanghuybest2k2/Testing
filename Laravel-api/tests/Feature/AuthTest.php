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
    public function testRegister()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password',
        ];

        $response = $this->json('POST', '/api/register', $data);
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
    public function test_login_and_get_auth_token()
    {
        // Tạo tài khoản user mới
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'role_as' => 0,
        ]);

        // Gửi yêu cầu đăng nhập với email và password đúng
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password',
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
}
