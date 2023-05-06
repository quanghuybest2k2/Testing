<?php

namespace Tests\Feature;

use App\Models\Subscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class SubscriberTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public $email = 'abc@gmail.com';

    public function testStoreSubscriber()
    {
        // Kiểm tra nếu email đã tồn tại
        if (Subscriber::where('email', $this->email)->exists()) {
            $this->assertTrue(false, 'Bạn đã đăng ký rồi!');
        }
        // Send a POST request to the API with the email parameter
        $response = $this->post('/api/subscribers', ['email' => $this->email]);

        if (!$response) {
            $response->assertStatus(400);
        }
        // Assert that the response has a 200 status code
        $response->assertStatus(Response::HTTP_OK);

        // Assert that the response contains the expected message
        $response->assertJson(['message' => 'Đăng ký thành công.']);

        // Assert that a subscriber with the given email was created
        $this->assertDatabaseHas('subscribers', ['email' => $this->email]);
    }
}
