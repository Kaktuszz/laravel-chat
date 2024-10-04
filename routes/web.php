<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/chats', [ChatController::class,'index']);
    Route::post('/chats', [ChatController::class,'store']);

    Route::get('/chat-room/{chat_room_id}', [MessageController::class, 'index']);
    Route::post('/chat-room-send/{chat_room_id}', [MessageController::class, 'store']);
});

require __DIR__.'/auth.php';
