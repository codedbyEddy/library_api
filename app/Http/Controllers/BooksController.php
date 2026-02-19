<?php

namespace App\Http\Controllers;
use App\Models\Book;
use App\Models\Borrow;

use Illuminate\Http\Request;

class BooksController extends Controller
{
     public function addBook(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'auhtor' => 'required|string|max:255',
            'total_copies' => 'required|integer',
        ]);

        $book = Book::Create([
            'title' => $request->title,
            'auhtor'=> $request->auhtor,
            'total_copies'=> $request->total_copies,
            'available_copies'=> $request->total_copies,
            
        ]);

        return response()->json([
            'success' =>'Book successfully added',
            'data' => $book
        ]);
    }

    public function listBooks(){
        $books = Book::all();
        return response()->json($books);
    }

    public function borrowBook(Request $request){

        $borrowedAt = now();
        $dueDate = $borrowedAt->copy()->addDays(7);
        
        $validated= $request->validate([
            'book_id' => 'required|exists:books,id',
            'user_id' => 'required|exists:users,id',
            'due_date'=> 'required'
        ]);

        $book = Book::where('id', $validated['book_id'])
                ->where('available_copies', '>', 0)
                ->first();

        $book->decrement('available_copies');

        $book = Borrow::create([
            'user_id' => $request->user_id,
            'book_id' => $request->book_id,
            'borrowed_at'=> now(),
            'due_date' => $request->due_date
        ]);

        return response()->json([
            'message' => 'Book borrowed successfully',
            'borrowed_at'=> $borrowedAt,
            'due_date' => $dueDate
        ]);
    }

    public function returnBook(Request $request){
        // dd($request->all());
        $request->validate([
            'borrows_id' => 'required',
        ]);

        $borrow = Borrow::find($request->borrows_id);

        $borrow->update(['returned_at' => now()]);

        $book = Book::where('id', $borrow->book_id)->increment('available_copies');


        return response()->json([
            'message' => 'Book returned successfully',
            'returned_at'=> now(),
            ]);
    }

     public function bookAvailability($id){
        
        // $request->validate([
        //     'title' => 'required|string|max:255',
        //     'available_copies' => 'required|integer',
        // ]);

        $book = Book::find($id);

        return response()->json([
            'title' => $book->title,
            'available_copies' => $book->available_copies,
        ]);
    }

    public function editBook(Request $request, $id){
        $request->validate([
            'title' => 'required|string|max:255',
            'auhtor' => 'required|string|max:255',
            'total_copies' => 'required|integer',
        ]);
        $book = Book::find($id);

        $book->update([
            'title' => $request->title,
            'auhtor' => $request->auhtor,
            'total_copies' => $request->total_copies,
    ]);

        return response()->json([
            'message'=>'Book edited and updated Successfully']);
    }

    public function viewBorrowed(){

        $borrow = Borrow::with('book', 'user')->get();

        return response()->json($borrow);
    }

    public function deleteBook(Request $request, $id){
        $book = Book::find($id);

        $book->delete();
        
        return response()->json([
            'message' => 'Book deleted successfully']);

    }
}
