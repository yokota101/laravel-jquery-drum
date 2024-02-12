<?php declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OAuth\CallbackFromProviderController;
use App\Http\Controllers\OAuth\RedirectToProviderController;

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

// pages
Route::get('/', 'App\Http\Controllers\TopController@index');
Route::get('/philosophy', function () {return view('philosophy');});
Route::get('/watch', 'App\Http\Controllers\WatchController@getIndex');
Route::get('/mypage', 'App\Http\Controllers\MypageController@index');
Route::get('/user', 'App\Http\Controllers\MypageController@userPage');
Route::get('/post', 'App\Http\Controllers\PostController@index');
Route::get('/post-edit', 'App\Http\Controllers\PostController@postEdit');
Route::post('/post-confirm', 'App\Http\Controllers\PostController@postConfirm');
Route::post('/post-complete', 'App\Http\Controllers\PostController@postComplete');
Route::get('/profile-edit', 'App\Http\Controllers\ProfileController@index');
Route::get('/ranking', 'App\Http\Controllers\TopController@ranking');
Route::get('/archive', 'App\Http\Controllers\TopController@archive');
Route::post('/profile-edit/confirm', 'App\Http\Controllers\MypageController@postProfileConfirm');
Route::post('/profile-edit/complete', 'App\Http\Controllers\MypageController@postProfileComplete');

Route::get('logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

Route::fallback(function() {
    return response()->view('errors.404', [], 404);
});

// api
Route::get('/oauth/{provider}/redirect', RedirectToProviderController::class)->name('oauth.redirect');
Route::get('/oauth/{provider}/callback', CallbackFromProviderController::class)->name('oauth.callback');
Route::get('/top-selected-category', 'App\Http\Controllers\TopController@get');
Route::get('/rank-selected-category', 'App\Http\Controllers\TopController@getRankList');
Route::get('/archive-selected-category', 'App\Http\Controllers\TopController@getArchiveList');
Route::post('/post-image', 'App\Http\Controllers\MypageController@postImage');
Route::get('/youtubeinfo', 'App\Http\Controllers\PostController@getYoutube');
Route::post('/vote-update', 'App\Http\Controllers\WatchController@voteUpdate');
Route::post('/delete-post', 'App\Http\Controllers\PostController@deletePost');
Route::post('/delete-account', 'App\Http\Controllers\ProfileController@deleteAccount');
Route::get('/userpage-list', 'App\Http\Controllers\MypageController@getList');
Route::get('/mypage-list', 'App\Http\Controllers\MypageController@getListForMypage');