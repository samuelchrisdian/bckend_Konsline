<?php

use Illuminate\Http\Request;

Route::post('login', 'UserController@login'); //do login
Route::post('register', 'UserController@store'); //create petugas

Route::group(['middleware' => ['jwt.verify']], function () {
	Route::get('login/check', "UserController@LoginCheck"); //cek token
	Route::post('logout', "UserController@logout"); //logout
	Route::delete('admin/{id}', "LoginController@delete"); //delete petugas

	Route::get('siswa', "SiswaController@index"); //read siswa
	Route::post('siswa', 'SiswaController@store'); //create siswa
	Route::get('siswa/{limit}/{offset}', "SiswaController@getAll"); //read siswa
	Route::put('siswa/{id}', "SiswaController@update"); //update siswa
	Route::delete('siswa/{id}', "SiswaController@delete"); //delete siswa
	
	Route::get('guru', "GuruController@index"); //read guru
	Route::post('guru', 'GuruController@store'); //create guru
	Route::get('guru/{limit}/{offset}', "GuruController@getAll"); //read guru
	Route::put('guru/{id}', "GuruController@update"); //update guru
	Route::delete('guru/{id}', "GuruController@delete"); //delete guru
	
	Route::get('picture', "PictureController@index"); //read picture
	Route::get('picture/{limit}/{offset}', "PictureController@getAll"); //read picture
	Route::post('picture', 'PictureController@store'); //create picture
	Route::put('picture/{id}', "PictureController@update"); //update picture
	Route::delete('picture/{id}', "PictureController@delete"); //delete picture
	
	Route::get('quote', "QuoteController@index"); //read quote
	Route::get('quote/{limit}/{offset}', "QuoteController@getAll"); //read quote
	Route::post('quote', 'QuoteController@store'); //create quote
	Route::put('quote/{id}', "QuoteController@update"); //update quote
	Route::delete('quote/{id}', "QuoteController@delete"); //delete quote
	
	Route::get('video', "VideoController@index"); //read video
	Route::get('video/{limit}/{offset}', "VideoController@getAll"); //read video
	Route::post('video', 'VideoController@store'); //create video
	Route::put('video/{id}', "VideoController@update"); //update video
	Route::delete('video/{id}', "VideoController@delete"); //delete video
});
