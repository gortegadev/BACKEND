<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\Book;

class BooksApiTest extends TestCase
{
    use refreshDatabase;

    /** @test */

    function can_get_all_books(){
        $books = Book::factory(4)->create();

        $response = $this->getJson(route('books.index'));

        $response->assertJsonFragment([
            'title' => $books[0]->title
        ]);

        $response->assertJsonFragment([
            'title' => $books[2]->title
        ]);

    }

    /** @test */

    function can_get_one_books(){
        $book = Book::factory()->create();

        $this->getJson(route('books.show',$book))
        ->assertJsonFragment([
            'title' => $book->title
        ]);

    }

    /** @test */

    function can_create_books(){
        $this->postJson(route('books.store'),[])
        ->assertJsonValidationErrorFor('title');

       $this->postJson(route('books.store'),[
        'title' => 'my new book'
       ])->assertJsonFragment([
            'title' => 'my new book'
        ]);

        $this->assertDatabaseHas('books',[
            'title' => 'my new book'
        ]);

    }

    /** @test */

    function can_update_books(){
        $book = Book::factory()->create();

        $this->patchJson(route('books.update',$book),[])
        ->assertJsonValidationErrorFor('title');


        $response = $this->patchJson(route('books.update',$book),[
            'title' => 'edited book'
        ])->assertJsonFragment([
            'title' => 'edited book'
        ]);

        $this->assertDatabaseHas('books',[
            'title' => 'edited book'
        ]);

    }

    /** @test */

    function can_delete_books(){
        $book = Book::factory()->create();

        $this->deleteJson(route('books.destroy',$book))->assertNoContent();

        $this->assertDatabaseCount('books',0);

    }
}
