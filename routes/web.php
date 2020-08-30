<?php

use Illuminate\Support\Facades\Route;

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

// ketika membuat login dan pertama kali memunculkan login bukan halaman welcome
Route::get('/', function () {
    return redirect('login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::match(['GET', 'POST'], '/register', function () {
    return redirect('login');
})->name('register');

//buat nampilin template Route::view('template','layouts.template');

Route::resource('user', 'UserController');
Route::resource('supplier','SupplierController')->except(['show']);
Route::resource('pegawai','PegawaiController')->except(['show']);
Route::resource('kategori','KategoriController')->except(['show']);
Route::resource('produk','ProdukController')->except(['show']);
Route::resource('transaksi_masuk','TransaksiMasukController')->except(['show']);
