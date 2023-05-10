<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuthTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public $name = 'Nguyễn Văn Dũng';
    public $email = 'dungnguyen@gmail.com';
    public $password = '12345678';

    public function testRegister()
    {
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'password' => $this->faker->password(8),
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 200,
            'username' => $userData['name'],
            'message' => 'Đăng ký thành công.',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => $userData['name'],
            'email' => $userData['email'],
        ]);

        $user = User::where('email', $userData['email'])->first();
        $this->assertTrue(Hash::check($userData['password'], $user->password));
    }
    public function test_register_with_missing_fields()
    {
        $response = $this->postJson('/api/register');

        $response->assertStatus(400);
        $response->assertJsonValidationErrors(['name', 'email', 'password']);
    }
    public function test_login()
    {
        // Tạo tài khoản user mới
        $user = User::factory()->create([
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

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
