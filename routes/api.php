<?php
Use App\Article;
Use App\Randompik;
use Illuminate\Http\Request;
use App\Http\Middleware\Cors;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, Content-Type');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Auth::routes();
//Auth::routes(['verify' => true]);




Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });



//авторизация
Route::post('login', 'Auth\LoginController@login');


Route::group(['middleware' => ['web']], function () {
    // your routes here
    Route::get('articles/{city_id}', 'ArticleController@index');
Route::get('articles/{article}', 'ArticleController@userArticles');
Route::get('articles/detail/{article}', 'ArticleController@detail');

});


Route::get('articlesType', 'DataloadController@index');

Route::post('articles/myfile', 'ArticleController@myfile');
Route::post('articles/{token}', 'ArticleController@token');





/*-----API----------------*/

Route::group(['middleware' => ['auth:api'] ], function() {
    /*---БЛОГ---------------*/
        Route::post('blog', 'BlogController@create');
        Route::get('show_for_user/{id}', 'BlogController@show_for_user');
    /*----------------------*/

    /*-----Загрузка файлов------------*/

	// обновление данных пользователя
    Route::post('users', 'UserController@update');

    /*--------------------------------*/

    Route::post('articles', 'ArticleController@store');
    Route::put('articles/{article}', 'ArticleController@update');
    Route::delete('articles/{article}', 'ArticleController@delete');

	/*КАТЕГОРИИ*/
	Route::get('category/{id}', 'ArticleController@userCategory');
	Route::post('categories', 'CategoryController@create');
	Route::delete('categories/{category}', 'CategoryController@destroy');

	Route::post('comments', 'CommentController@create');
	Route::get('comments/{article}/{user}', 'CommentController@show');
	Route::delete('comments/{comment}', 'CommentController@destroy');
	Route::get('response', 'ResponseController@index');


	Route::get('appeals', 'AppealController@index');
	Route::delete('appeals/{appeal}', 'AppealController@destroy');

	Route::post('subscribes', 'SubscribeController@store');
	Route::post('subscribes/{id}', 'SubscribeController@show');
	Route::delete('subscribes/{subscribe}', 'SubscribeController@destroy');
	/*----АДМИНСКАЯ ЧАСТЬ-------------------*/
	Route::get('admin/articles', 'ArticleController@adminArticles');

	Route::post('admin/cities', 'CitiesController@create');
	Route::delete('admin/cities/{city}', 'CitiesController@destroy');

	// загрузка файла
	Route::post('uploadFile', 'FileController@uploadFile');

	// добавление типа объявления

	Route::post('types', 'ArticleController@addType');
	Route::delete('types', 'ArticleController@destroyType');

});


Route::get('types', 'ArticleController@getTypes');

Route::get('getFile/{id}', 'FileController@getFile');



Route::get('blog', 'BlogController@index');


Route::get('verify/{token}', 'Auth\VerificationController@verify');
Route::post('forgetpass', 'Auth\ResetPasswordController@forgetpass');

Route::post('checkToken', 'Auth\ResetPasswordController@checkToken');
Route::post('saveNewPass', 'Auth\ResetPasswordController@saveNewPass');


Route::get('cities', 'CitiesController@index');


Route::get('users', 'UserController@index');
Route::get('users/{user}', 'UserController@show');


/*===========================================================*/
Route::get('categories', 'CategoryController@index');
Route::get('categories/{category}', 'CategoryController@show');





Route::get('randomimg', 'RandompictureController@randomimg');
Route::get('randomuser', 'RandompictureController@randomuser');

Route::post('register', 'Auth\RegisterController@register');


Route::get('logout', 'Auth\LoginController@logout');

Route::get('comments/{article}/{user}', 'CommentController@show');

Route::post('appeals', 'AppealController@create');

Route::post('sendVerifyEmail', 'Auth\RegisterController@sendVerifyEmail');

Route::get('checktoken', 'Auth\LoginController@checkToken');


Route::get('cars', 'CarController@index');
Route::post('cars', 'CarController@add');
Route::delete('cars/{car}', 'CarController@destroy');
Route::put('cars/{car}', 'CarController@update');

























