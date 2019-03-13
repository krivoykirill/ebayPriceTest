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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/add', 'HomeController@add')->name('add');
Route::post('/add/new','HomeController@new');
Route::get('/view/{id}', 'HomeController@view')->name('view');
//to do delete  https://itsolutionstuff.com/post/how-to-delete-multiple-records-using-checkbox-in-laravel-5-example.html
Route::post('/delete','HomeController@deleteAll')->name('delete');
Route::get('/signup',function () {
    if (Auth::check()){
        return redirect('home');
    }
    else {
        return view('signup');
    }
})->name('signup');
Route::get('/testpost',function() {
    return view('post');
});