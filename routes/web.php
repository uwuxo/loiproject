<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\Logout2Controller;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserNewController;
use App\Http\Controllers\User\RegisterNewController;
use App\Http\Controllers\User\LoginNewController;
use App\Http\Controllers\User\RoomController;
use App\Http\Controllers\User\CourseController;
use App\Http\Controllers\User\UserShowController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/login', [LoginNewController::class, 'login'])->name('users.login');
Route::post('/user-login', [LoginNewController::class, 'loginOnPage'])->name('login')->middleware('guest');
Route::prefix('/user')->middleware('auth')->group(function(){
    Route::get('show', [UserShowController::class, 'show'])->name('show.user');
});
Route::prefix('/admin')->middleware('role:super-admin')->group(function(){
    Route::get('/dashboard', [UserNewController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [UserNewController::class, 'index'])->name('users.index');
    Route::get('/user/edit/{id}', [UserNewController::class, 'edit'])->name('user.edit');
    Route::post('/user/update/{id}', [UserNewController::class, 'update'])->name('user.update');
    Route::post('/user/destroy/{id}', [UserNewController::class, 'destroy'])->name('user.destroy');

    Route::get('/groups', [CourseController::class, 'index'])->name('group.index');
    Route::get('/group/create', [CourseController::class, 'create'])->name('group.create');
    Route::get('/group/edit/{id}', [CourseController::class, 'edit'])->name('group.edit');
    Route::post('/group/create', [CourseController::class, 'register'])->name('group.register');
    Route::post('/group/update/{id}', [CourseController::class, 'update'])->name('group.update');
    Route::post('/group/destroy/{id}', [CourseController::class, 'destroy'])->name('group.destroy');

    Route::get('/rooms/{id}', [RoomController::class, 'index'])->name('rooms');
    Route::get('/room/add/{id}', [RoomController::class, 'create'])->name('room.add');
    Route::get('/room/edit/{id}', [RoomController::class, 'edit'])->name('room.edit');
    Route::post('/room/update/{id}', [RoomController::class, 'update'])->name('room.update');
    Route::post('/room/store/{id}', [RoomController::class, 'store'])->name('room.store');
    Route::post('/room/destroy/{id}', [RoomController::class, 'destroy'])->name('room.destroy');

    Route::get('/register', [RegisterNewController::class, 'registerShow'])->name('user.register');
    Route::post('/user-register', [RegisterNewController::class, 'register'])->name('register');
    
});

Route::prefix('/auth')->group(function(){
	// Authentication Routes...
	Route::post('/login', LoginController::class)->middleware('guest');
    Route::post('/logout', LogoutController::class);
    Route::post('/logout2', Logout2Controller::class)->name('logout2');
    Route::post('/register', RegisterController::class)->middleware('guest');
});
