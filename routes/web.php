<?php
use Illuminate\Http\Request;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\PostController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home');
});


Auth::routes();

Route::get('/home', 'HomeController@index');

Route::group(['domain' => 'localhost:4200/#/'], function () {
  Route::get('verification/{token}', 'Auth\RegisterController@verify')->name('register.verify');
});

  
  
Route::get('posts',[PostController::class,'index'])->name('posts.index');
Route::get('posts/create',[PostController::class,'create'])->name('posts.create');
Route::post('posts/store',[PostController::class,'store'])->name('posts.store');
