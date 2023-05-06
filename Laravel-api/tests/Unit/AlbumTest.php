<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class AlbumTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function testReturnsCorrectData()
    {
        $response = $this->get('/api/getAlbumPet');

        $response->assertStatus(200);
        $responseContent = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('status', $responseContent);
        $this->assertArrayHasKey('pets', $responseContent);

        if (empty($responseContent['pets'])) {
            $this->fail('Không có con pet nào cả!');
        } else {
            $this->assertIsArray($responseContent['pets']);
            foreach ($responseContent['pets'] as $pet) {
                $this->assertArrayHasKey('id', $pet);
                $this->assertArrayHasKey('user_id', $pet);
                $this->assertArrayHasKey('category_id', $pet);
                $this->assertArrayHasKey('emotion', $pet);
                $this->assertArrayHasKey('image_pet', $pet);
                $this->assertArrayHasKey('created_at', $pet);
                $this->assertArrayHasKey('updated_at', $pet);
                $this->assertArrayHasKey('category', $pet);
                $this->assertIsArray($pet['category']);
                $this->assertArrayHasKey('id', $pet['category']);
                $this->assertArrayHasKey('slug', $pet['category']);
                $this->assertArrayHasKey('name', $pet['category']);
                $this->assertArrayHasKey('description', $pet['category']);
                $this->assertArrayHasKey('image', $pet['category']);
                $this->assertArrayHasKey('status', $pet['category']);
                $this->assertArrayHasKey('created_at', $pet['category']);
                $this->assertArrayHasKey('updated_at', $pet['category']);
                $this->assertArrayHasKey('user', $pet);
                $this->assertIsArray($pet['user']);
                $this->assertArrayHasKey('id', $pet['user']);
                $this->assertArrayHasKey('name', $pet['user']);
                $this->assertArrayHasKey('email', $pet['user']);
                $this->assertArrayHasKey('email_verified_at', $pet['user']);
                $this->assertArrayHasKey('role_as', $pet['user']);
                $this->assertArrayHasKey('created_at', $pet['user']);
                $this->assertArrayHasKey('updated_at', $pet['user']);
            }
        }
    }
}
