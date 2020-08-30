<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('agen','Api\AgenController');
Route::get('search_agen','Api\AgenController@search');
Route::post('login_pegawai','Api\PegawaiController@login_pegawai');
Route::get('get_all','Api\KategoriController@get_all');
Route::get('get_produk','Api\ProdukController@get_produk_kategori');
Route::post('add_cart','Api\TransaksiController@addCart');
Route::post('get_cart','Api\TransaksiController@get_cart'); 