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

Route::redirect('/', 'home');

Route::view('/home', 'home')->name('home');

Route::get('/dnsresolv/last', 'DnsResolvController@last')
        ->name('dnsresolv.last');

Route::get('/dnsresolv/show', 'DnsResolvController@show')
        ->name('dnsresolv.show');

Route::get('/dnsresolv/search', 'DnsResolvController@search')
        ->name('dnsresolv.search');

Route::get('/event/last', 'EventController@last')->name('event.last');

Route::get('/event/show', 'EventController@show')->name('event.show');

Route::get('/event/search', 'EventController@search')->name('event.search');
