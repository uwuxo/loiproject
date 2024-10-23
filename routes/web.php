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
Route::prefix('/admin')->middleware('auth')->group(function(){
    Route::get('/dashboard', [UserNewController::class, 'dashboard'])->name('dashboard');

//User
    Route::get('/users', [UserNewController::class, 'index'])->middleware([
        'role:super-admin|user-admin|user-edit|user-view'
        ])->name('users.index');
    Route::get('/user/edit/{id}', [UserNewController::class, 'edit'])->middleware([
        'role:super-admin|user-admin|user-edit'
        ])->name('user.edit');
    Route::post('/user/update/{id}', [UserNewController::class, 'update'])->middleware([
        'role:super-admin|user-admin|user-edit'
        ])->name('user.update');
    Route::post('/user/destroy/{id}', [UserNewController::class, 'destroy'])->middleware([
        'role:super-admin|user-admin'
        ])->name('user.destroy');
//Group
    Route::get('/groups', [CourseController::class, 'index'])->middleware([
        'role:super-admin|group-admin|group-edit|group-view'
        ])->name('group.index');
    Route::get('/group/create', [CourseController::class, 'create'])->middleware([
        'role:super-admin|group-admin'
        ])->name('group.create');
    Route::get('/group/edit/{id}', [CourseController::class, 'edit'])->middleware([
        'role:super-admin|group-admin|group-edit'
        ])->name('group.edit');
    Route::post('/group/create', [CourseController::class, 'register'])->middleware([
        'role:super-admin|group-admin'
        ])->name('group.register');
    Route::post('/group/update/{id}', [CourseController::class, 'update'])->middleware([
        'role:super-admin|group-admin|group-edit'
        ])->name('group.update');
    Route::post('/group/destroy/{id}', [CourseController::class, 'destroy'])->middleware([
        'role:super-admin|group-admin'
        ])->name('group.destroy');
//Room
    Route::get('/rooms/{id}', [RoomController::class, 'index'])->middleware([
        'role:super-admin|group-admin|group-edit|group-view'
        ])->name('rooms');
    Route::get('/room/add/{id}', [RoomController::class, 'create'])->middleware([
        'role:super-admin|group-admin'
        ])->name('room.add');
    Route::get('/room/edit/{id}', [RoomController::class, 'edit'])->middleware([
        'role:super-admin|group-admin'
        ])->name('room.edit');
    Route::post('/room/update/{id}', [RoomController::class, 'update'])->middleware([
        'role:super-admin|group-admin'
        ])->name('room.update');
    Route::post('/room/store/{id}', [RoomController::class, 'store'])->middleware([
        'role:super-admin|group-admin'
        ])->name('room.store');
    Route::post('/room/destroy/{id}', [RoomController::class, 'destroy'])->middleware([
        'role:super-admin|group-admin'
        ])->name('room.destroy');

    Route::get('/register', [RegisterNewController::class, 'registerShow'])->middleware([
        'role:super-admin|user-admin'
        ])->name('user.register');
    Route::post('/user-register', [RegisterNewController::class, 'register'])->middleware([
        'role:super-admin|user-admin'
        ])->name('register');
    
});

Route::prefix('/auth')->group(function(){
	// Authentication Routes...
	Route::post('/login', LoginController::class)->middleware('guest');
    Route::post('/logout', LogoutController::class);
    Route::post('/logout2', Logout2Controller::class)->name('logout2');
    Route::post('/register', RegisterController::class)->middleware('guest');
});
