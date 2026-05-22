<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_book_is_created_with_valid_data()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/api/books', [
                'title' => 'Mon premier livre',
                'author' => 'Victor Hugo',
                'summary' => 'Un livre de test',
                'isbn' => '9781234567897',
            ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('books', [
            'title' => 'Mon premier livre',
        ]);
    }

    public function test_book_is_not_created_with_invalid_data()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/api/books', [
                'title' => 'AB'
            ]);

        $response->assertStatus(422);

        $this->assertDatabaseMissing('books', [
            'title' => 'AB'
        ]);
    }

    public function test_book_is_not_created_when_user_is_not_authenticated()
    {
        $response = $this->postJson('/api/books', [
            'title' => 'Livre sans connexion'
        ]);

        $response->assertStatus(401);

        $this->assertDatabaseMissing('books', [
            'title' => 'Livre sans connexion'
        ]);
    }
}