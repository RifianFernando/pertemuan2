<?php

namespace App\Http\Controllers;

use App\Models\author;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function redirectToCreateBookPage()
    {
        $authors = author::all();
        return view('create_book', compact('authors'));
    }

    public function createBook(Request $request)
    {
        $validated = $request->validate([
            'book_name_input' => 'required|unique:App\Models\Book,book_name|max:255',
            'book_image_input' => 'required|mimes:jpg,png',
            'author_input' => 'required|string'
        ]);

        // dd('masuk');

        $path = $request->file('book_image_input');
        $book = Book::create([
            'book_name' => $request->book_name_input,
            'book_image_path' => $path,
            'author_id' => $request->author_input
        ]);

        // convert ke string nama asli + book id
        $fileName = $book->id . $path->getClientOriginalName();
        $path->storeAs('public/image_book', $fileName);
        $book->book_image_path = 'image_book/' . $fileName;
        $book->save();

        return redirect('/home');
    }

    public function updateBookPage($id)
    {
        $book = Book::findOrFail($id);

        return view('update_book', ["book" => $book]);
    }

    public function updateBook(Request $request, $id1)
    {

        $validated = $request->validate([
            'book_name_input' => 'required|unique:App\Models\Book,book_name|max:255',
            'book_image_input' => 'required|mimes:jpg,png',
            'author_input' => 'required|string'
        ]);

        $path = $request->file('book_image_input');

        // update book method
        Book::findOrFail($id1)->update([
            'book_name' => $request->book_name_input,
            'book_image_path' => $path,
            'author' => $request->author_input
        ]);

        // search for the book yang udh di update
        $book = Book::find($id1);
        $fileName = $book->id . $path->getClientOriginalName();
        $path->storeAs('public/image_book', $fileName);
        $book->book_image_path = 'image_book/' . $fileName;
        $book->save();

        return redirect('/home');
    }

    public function deleteBook($id)
    {
        // cara satu
        // Book::findOrFail($id)->delete();

        // cara dua
        Book::destroy($id);

        return redirect('/home');
    }
}
