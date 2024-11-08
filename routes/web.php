<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\Logout2Controller;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserNewController;
use App\Http\Controllers\User\RegisterNewController;
use App\Http\Controllers\User\LoginNewController;
use App\Http\Controllers\User\RoomController;
use App\Http\Controllers\User\CourseController;
use App\Http\Controllers\User\UserShowController;
use App\Models\Room;

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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');
Route::get('course/detail/{id}', [App\Http\Controllers\HomeController::class, 'detail'])->name('detail')->middleware('auth');

Route::get('/login', [LoginNewController::class, 'login'])->name('users.login');
Route::post('/user-login', [LoginNewController::class, 'loginOnPage'])->name('login')->middleware('guest');
Route::prefix('/user')->middleware('auth')->group(function(){
    Route::get('show', [UserShowController::class, 'show'])->name('show.user');
});
Route::prefix('/admin')->middleware(['auth', 'user.type'])->group(function(){
    Route::get('/dashboard', [UserNewController::class, 'dashboard'])->name('dashboard');
    Route::get('/logged', [App\Http\Controllers\HomeController::class, 'loggedIn'])->name('logged');
    Route::get('/attendance', [App\Http\Controllers\AttendanceController::class, 'getAttendanceReport'])->name('attendance');
    Route::get('/attendance/export', [App\Http\Controllers\AttendanceController::class, 'export'])->name('attendance.export');

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
    Route::get('/courses', [CourseController::class, 'index'])->middleware([
        'role:super-admin|course-admin|course-edit|course-view'
        ])->name('group.index');
    Route::get('/course/create', [CourseController::class, 'create'])->middleware([
        'role:super-admin|course-admin'
        ])->name('group.create');
    Route::get('/course/edit/{id}', [CourseController::class, 'edit'])->middleware([
        'role:super-admin|course-admin|course-edit'
        ])->name('group.edit');
    Route::post('/course/create', [CourseController::class, 'register'])->middleware([
        'role:super-admin|course-admin'
        ])->name('group.register');
    Route::post('/course/update/{id}', [CourseController::class, 'update'])->middleware([
        'role:super-admin|course-admin|course-edit'
        ])->name('group.update');
    Route::post('/course/destroy/{id}', [CourseController::class, 'destroy'])->middleware([
        'role:super-admin|course-admin'
        ])->name('group.destroy');

    Route::get('/gateway', [RoomController::class, 'gateway'])->name('gateway');
    Route::get('/logged-room/{id}', [RoomController::class, 'loggedsRoom'])->name('loggeds.room');

//Room
    // Route::get('/rooms/{id}', [RoomController::class, 'index'])->middleware([
    //     'role:super-admin|group-admin|group-edit|group-view'
    //     ])->name('rooms');
    Route::get('/room/add', [RoomController::class, 'create'])->middleware([
        'role:super-admin|group-admin'
        ])->name('room.add');
    Route::get('/room/edit/{id}', [RoomController::class, 'edit'])->middleware([
        'role:super-admin|group-admin'
        ])->name('room.edit');
    Route::post('/room/update/{id}', [RoomController::class, 'update'])->middleware([
        'role:super-admin|group-admin'
        ])->name('room.update');
    Route::post('/room/store', [RoomController::class, 'store'])->middleware([
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
Route::get('/test', [App\Http\Controllers\TestController::class, 'index']);
Route::post('/test', [App\Http\Controllers\TestController::class, 'store'])->name('test.store');
Route::get('/logs', function () {
    $logFile = storage_path('logs/laravel.log');
    if (file_exists($logFile)) {
        $logs = file_get_contents($logFile);
        return '<pre>' . $logs . '</pre>';
    } else {
        return 'Log file not found.';
    }
})->name('view-logs');
Route::prefix('/auth')->group(function(){
	// Authentication Routes...
	Route::post('/login', LoginController::class)->middleware('guest');
    Route::post('/logout', LogoutController::class);
    Route::post('/logout2', Logout2Controller::class)->name('logout2');
    Route::post('/register', RegisterController::class)->middleware('guest');
});
