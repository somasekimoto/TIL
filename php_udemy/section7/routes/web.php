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


Route::get('shops/index', 'ShopController@index');

Route::group(['prefix' => 'contact', 'middleware' => 'auth'], function(){
    Route::get('index', 'ContactFormController@index')->name('contact.index'); 
    Route::get('create', 'ContactFormController@create')->name('contact.create'); 
    Route::post('store', 'ContactFormController@store')->name('contact.store'); 
    Route::get('show/{id}', 'ContactFormController@show')->name('contact.show'); 
    Route::get('edit/{id}', 'ContactFormController@edit')->name('contact.edit'); 
    Route::post('update/{id}', 'ContactFormController@update')->name('contact.update'); 
    Route::post('destroy/{id}', 'ContactFormController@destroy')->name('contact.destroy'); 
});
// Route::resource('contacts', 'ContactFormController')->only([
//     'index', 'show'
// ]);

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
