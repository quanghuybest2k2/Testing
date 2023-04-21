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
    public function testRegisterWithValidData()
    {
        $email = "doanquanghuy@gmail.com";
        $name = "Đoàn Quang Huy";
        User::factory()->create([
            'email' => $email,
        ]);

        $response = $this->postJson('/api/register', [
            'name' => $name,
            'email' => $email,
            'password' => '12345678',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'Đã tồn tại user!',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => $name,
            'email' => $email,
        ]);
    }
}
