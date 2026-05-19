<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BookController extends Controller
{
    public function index()
    {
        return BookResource::collection(
            Book::paginate(2)
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:3|max:255',
            'author' => 'required|string|min:3|max:100',
            'summary' => 'required|string|min:10|max:500',
            'isbn' => 'required|string|size:13|unique:books,isbn',
        ]);

        $book = Book::create($validated);

        return new BookResource($book);
    }

    public function show(Book $book)
    {
        $book = Cache::remember(
            'book_' . $book->id,
            3600,
            function () use ($book) {
                return new BookResource($book);
            }
        );

        return $book;
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:3|max:255',
            'author' => 'required|string|min:3|max:100',
            'summary' => 'required|string|min:10|max:500',
            'isbn' => 'required|string|size:13|unique:books,isbn,' . $book->id,
        ]);

        $book->update($validated);

        Cache::forget('book_' . $book->id);

        return new BookResource($book);
    }

    public function destroy(Book $book)
    {
        Cache::forget('book_' . $book->id);

        $book->delete();

        return response()->noContent();
    }
}