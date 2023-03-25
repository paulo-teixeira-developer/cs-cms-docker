<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/** controladores**/

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\PostController;
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

/** login **/
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});
Route::group(['prefix' => 'auth', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

/** users **/
Route::group(['prefix' => 'users','middleware' => 'auth:sanctum'], function () {
    Route::get('/listing', [UserController::class, 'listing']);
    Route::post('/store', [UserController::class, 'store']);
    Route::get('/show/{id}', [UserController::class, 'show']);
    Route::put('/update/{id}', [UserController::class, 'update']);
    Route::put('/update-credential/{id}', [UserController::class, 'updateCredential']);
    Route::post('/update-img/{id}', [UserController::class, 'updateImg']);
    Route::delete('/delete/{id}', [UserController::class, 'delete']);
});

/** categorias **/
Route::group(['prefix' => 'categories','middleware' => 'auth:sanctum'], function () {
    Route::get('/listing', [CategoryController::class, 'listing']);
    Route::post('/store', [CategoryController::class, 'store']);
    Route::get('/show/{id}', [CategoryController::class, 'show']);
    Route::put('/update/{id}', [CategoryController::class, 'update']);
    Route::delete('/delete/{id}', [CategoryController::class, 'delete']);
});

/** files **/
Route::get('files/streaming/{path_primary}/{path_secondary}/{file}/', [FileController::class, 'streaming']);
Route::get('files/streaming/user-img/{file}/', [FileController::class, 'streamingPrivate']);
Route::group(['prefix' => 'files','middleware' => 'auth:sanctum'], function () {
    Route::get('/listing', [FileController::class, 'listing']);
    Route::get('/listing-by-path', [FileController::class, 'listingByPath']);
    Route::post('/store', [FileController::class, 'store']);
    Route::get('/show/{id}', [FileController::class, 'show']);
    Route::delete('/delete/{id}', [FileController::class, 'delete']);
    Route::get('/replace-name/', [FileController::class, 'replaceName']);
});

/** posts **/
Route::group(['prefix' => 'posts','middleware' => 'auth:sanctum'], function () {
    Route::get('/listing', [PostController::class, 'listing']);
    Route::post('/store', [PostController::class, 'store']);
    Route::get('/show/{id}', [PostController::class, 'show']);
    Route::put('/update/{id}', [PostController::class, 'update']);
    Route::delete('/delete/{id}', [PostController::class, 'delete']);
});

/** ------------ PUBLIC ------------ **/

/** posts **/
Route::group(['prefix' => 'posts/ext'], function () {
    Route::get('/listing', [PostController::class, 'extListing']);
    Route::get('/list-the-latest', [PostController::class, 'extListTheLatest']);
    Route::get('/show-by-slug/{slug}', [PostController::class, 'extShowBySlug']);
});

/** categories **/
Route::group(['prefix' => 'categories/ext'], function () {
    Route::get('/listing', [CategoryController::class, 'extListing']);
});
