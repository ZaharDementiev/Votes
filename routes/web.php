<?php

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
//
//Route::get(
//    '/socialite/{provider}',
//    [
//        'as' => 'socialite.auth',
//        function ( $provider ) {
//            return \Socialite::driver( $provider )->redirect();
//        }
//    ]
//);
//
//Route::get('/oauth/{provider}', function ($provider) {
//    $user = \Socialite::driver($provider)->user();
//    dd($user);
//});


use Illuminate\Support\Facades\Route;

Auth::routes(['verify' => true]);
Route::get('last', function () {
   dd(auth()->user()->last_online_at);
});

//chat routes

Route::get('chat/{name}', 'MessageController@chat')->name('chat');
Route::get('mark-messages/{id}', 'MessageController@markMessages');
Route::get('/private-messages/{user}', 'MessageController@privateMessages')->name('privateMessages');
Route::post('/private-messages/{user}', 'MessageController@sendPrivateMessage')->name('privateMessages.store');
Route::get('/dialogs', 'MessageController@index')->name('dialogs');
Route::post('/dialogs', 'MessageController@index')->name('dialogs');
//end chat


Route::get('ta', 'HomeController@ms');
Route::get('registered', 'UserController@registered')->name('registered');
Route::get('sitemap.xml', 'HomeController@sitemap');

Route::get('options/{post_id}', 'PostController@getOptions');

Route::get('/search', 'HomeController@search');
Route::redirect('/home', '/my/tags');

Route::get('/mark-read', function () {
    auth()->user()->notifications()
        ->where('type', '!=', \App\Notifications\NewPost::class)
        ->where('type', '!=', \App\Notifications\PostToApprove::class)
        ->get()->markAsRead();
});
Route::get('messages-count', function () {
    return (int)auth()->user()->unreadMessages();
});


Route::get('/countNotifications', function () {
    return auth()->user()->unreadNotifications()
        ->where('type', '!=', \App\Notifications\NewPost::class)
        ->where('type', '!=', \App\Notifications\PostToApprove::class)
        ->count();
})->middleware('auth');

Route::get('/countUnapproved', function () {
    return \App\Post::where('approved', false)->count();
})->middleware('auth');

Route::get('/countPostNotifications', function () {
    return auth()->user()->unreadNotifications()
        ->where('type', '=', \App\Notifications\NewPost::class)
        ->count();
})->middleware('auth');



Route::post('comment/like', 'CommentController@like')->middleware('auth');
Route::post('comment/dislike', 'CommentController@dislike')->middleware('auth');
Route::post('comment/upvote', 'CommentController@upvote')->middleware('auth');
Route::post('comment/downvote', 'CommentController@downvote')->middleware('auth');
Route::post('comment-delete/{comment}', 'CommentController@destroy')->middleware('auth');

Route::get('edit/{slug}', 'PostController@edit')->name('edit-post');
Route::post('edit/{post}', 'PostController@update')->name('edit-post');
Route::post('comment-edit/{comment}', 'CommentController@update')->name('edit-comment');


Route::group(['prefix' => 'posts'], function () {

    Route::get('unapproved', 'PostController@unapproved')->name('unapproved')->middleware('can:approve-post');
    Route::get('add', 'PostController@addPost')->name('add-post')->middleware('auth');
//    Route::get('all/{filter}/{time?}', 'PostController@all')->name('list');
    Route::post('create', 'PostController@store')->name('store-post')->middleware('auth');
    Route::post('vote', 'PostController@vote')->name('vote')->middleware('auth');
    Route::post('like', 'PostController@like')->name('like')->middleware('auth');
    Route::post('dislike', 'PostController@dislike')->name('dislike')->middleware('auth');
    Route::post('upvote', 'PostController@upvote')->name('upvote')->middleware('auth');
    Route::post('downvote', 'PostController@downvote')->name('downvote')->middleware('auth');
    Route::post('favorite', 'PostController@favorite')->name('favorite')->middleware('auth');
    Route::post('unfavorite', 'PostController@unfavorite')->name('unfavorite')->middleware('auth');
    Route::delete('destroy/{post_id}', 'PostController@destroy')->name('destroy')->middleware('auth');
    Route::post('approve/{post}', 'PostController@approve')->name('approve')->middleware('can:approve-post');
});



//Route::middleware('cache.headers:public;max_age=300;etag')->group(function() {

    Route::get('/', 'PostController@live')->name('live');
    Route::post('/', 'PostController@live')->name('live');

    Route::get('my/tags', 'HomeController@index')->name('my-tags')->middleware('auth');
    Route::get('my/users', 'HomeController@byUser')->name('my-users')->middleware('auth');

    Route::post('my/tags', 'HomeController@index');
    Route::post('my/users', 'HomeController@byUser');

    Route::get('favorites/{filter?}', 'PostController@favorites')->name('favorites')->middleware('auth');
    Route::post('favorites', 'PostController@favorites')->name('favorites')->middleware('auth');


    Route::get('posts/{slug}', 'PostController@show')->name('show');
    Route::post('posts/{slug}', 'PostController@show')->name('show');

    Route::get('popular/{time?}', 'PostController@popular')->name('popular');
    Route::post('popular/{time?}', 'PostController@popular')->name('popular');
    Route::get('discussed/{time?}', 'PostController@discussed')->name('discussed');
    Route::post('discussed/{time?}', 'PostController@discussed')->name('discussed');
//Route::get('/', 'PostController@index')->name('home');
Route::get('/tag/edit', 'TagController@edit')->name('tag-edit');
Route::post('/tag/add', 'TagController@store')->middleware('can:add-tag')->name('tag-add');
Route::post('/tag/delete/{tag}', 'TagController@destroy')->middleware('can:add-tag')->name('tag-delete');
Route::post('/tag/edit/{tag}', 'TagController@update')->middleware('can:add-tag')->name('edit-tag');
Route::post('/tags/{filter?}', 'TagController@index')->name('tags');
Route::get('/tags/{filter?}', 'TagController@index')->name('tags');
Route::post('/tag/follow', 'TagController@follow');
Route::post('/tag/unfollow', 'TagController@unfollow');
Route::get('/tag/{slug}', 'TagController@show')->name('tag');
Route::post('/tag/{slug}', 'TagController@show')->name('tag');
Route::view('/notifications', 'notifications')->name('notifications');

//});

Route::group(['prefix' => 'user'], function () {

Route::get('{name}', 'UserController@posts')->name('profile')->middleware('auth');
Route::post('{name}', 'UserController@posts')->name('profile')->middleware('auth');
Route::get('{name}/follows', 'UserController@followsUsers')->name('profile-follows')->middleware('auth');
//Route::get('{name}/follows', 'UserController@follows')->name('profile-follows')->middleware('auth');
//Route::post('{name}/follows', 'UserController@follows')->name('profile-follows')->middleware('auth');
Route::get('{name}/followers', 'UserController@followers')->name('profile-followers')->middleware('auth');
Route::post('{name}/followers', 'UserController@followers')->name('profile-followers')->middleware('auth');
//--Zahar start--//
Route::get('{name}/feedbacks', 'UserController@feedbacks')->name('profile-feedbacks')->middleware('auth');
Route::get('{name}/transactions', 'UserController@transactions')->name('profile-transactions')->middleware('auth');
//--Zahar end--//
});

Route::post('/avatar', 'UserController@addAvatar')->middleware('auth');
Route::get('/settings', 'UserController@settings')->middleware('auth');
Route::post('/avatar-delete', 'UserController@deleteAvatar')->middleware('auth');
Route::post('/change-login', 'UserController@changeLogin')->name('login-change')->middleware('auth');
Route::post('/change-password', 'UserController@changePassword')->name('password-change')->middleware('auth');
Route::post('/change-email', 'UserController@changeEmail')->name('email-change')->middleware('auth');

Route::post('/users/follow', 'UserController@follow');
Route::post('/users/unfollow', 'UserController@unfollow');

//--Zahar start--//
Route::get('feedback/{transaction}', 'FeedbackController@index')->name('feedback')->middleware('auth');
Route::post('feedbacks/submit/{transaction}', 'FeedbackController@store')->name('feedback-submit')->middleware('auth');
Route::get('/transaction', 'TransactionController@transaction')->name('transaction')->middleware('auth');
Route::post('transaction/submit', 'TransactionController@make')->name('transaction-submit')->middleware('auth');

Route::get('virt', 'HomeController@virts')->name('virt')->middleware('auth');
