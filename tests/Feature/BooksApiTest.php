<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function can_get_all_books()
    {
        $books = Book::factory(4)->create();
        $response = $this->getJson(route('books.index'));

        $response->assertJsonFragment([
            'title' => $books[0]->title
        ]);
    }

    function test_can_get_one_book()
    {
        $book = Book::factory()->create();
        $response = $this->getJson(route('books.show', $book));

        $response->assertJsonFragment([
            'title' => $book->title
        ]);
    }

    /** @test */
    function can_create_books()
    {
        $this->postJson(route('books.store'), [])
            ->assertJsonValidationErrorFor('title');

        $response = $this->postJson(route('books.store'), [
            'title' => 'My new Test Book'
        ]);

        $response->assertJsonFragment([
            'title' => 'My new Test Book'
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'My new Test Book'
        ]);
    }

    /** @test */
    function can_update_books()
    {
        $book = Book::factory()->create();

        $this->patchJson(route('books.update', $book), [])
            ->assertJsonValidationErrorFor('title');

        $this->patchJson(route('books.update', $book), [
            'title' => 'Book UPDATED'
        ])->assertJsonFragment([
            'title' => 'Book UPDATED'
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'Book UPDATED'
        ]);
    }

    /** @test */
    function can_delete_books()
    {
        $book = Book::factory()->create();

        $this->deleteJson(route('books.destroy', $book))
            ->assertNoContent();

        $this->assertDatabaseCount('books', 0);
    }
}
