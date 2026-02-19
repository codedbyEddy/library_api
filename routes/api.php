<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BooksController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/list-books', [BooksController::class, 'listBooks']);
Route::post('/add-books', [BooksController::class, 'addBook']);
Route::post('/borrow-books', [BooksController::class, 'borrowBook']);
Route::post('/return-books', [BooksController::class, 'returnBook']);
Route::get('/books/availability/{id}', [BooksController::class, 'bookAvailability']);
Route::post('/edit-books/{id}', [BooksController::class, 'editBook']);
Route::post('/borrowed-books', [BooksController::class, 'viewBorrowed']);
Route::delete('/delete-books/{id}', [BooksController::class, 'deleteBook']);