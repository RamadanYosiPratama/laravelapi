<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Keranjang;
use App\Http\Resource\KeranjangResource;
use App\Produk;
use Validator;

class transaksiController extends Controller
{
    public function addCart(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input , [
            'username'=>'required|max:100',
            'kd_produk'=>'required|numeric',
            'jumlah'=>'required|numeric'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status'=>FALSE,
                'msg'=>$validator->errors()
            ],400);
        }

        $data['username'] = $request->input('username');
        $data['kd_produk'] = $request->input('kd_produk');
        $data['jumlah'] = $request->input('jumlah');


        // Cari data stok produk

        $data_produk = Produk::find($data['kd_produk']);
        $stok_produk = $data_produk->stok;

        // mencari jumlah produk atas produk itu sendiri dalam table keranjang

        $jumlah_barang_cart = Keranjang::where('kd_produk',$data['kd_produk'])->sum('jumlah');
        $stok_hasil = $stok_produk - $jumlah_barang_cart;

        // jika stok hasil lebih kecil dari jumlah yang di input kan maka akan menampilkan pesan stok barang tidak mencukupi

        if($stok_hasil < $data['jumlah'])
        {
            return response()->json([
                'status'=>FALSE,
                'msg'=>'Stok Barang tidak mencukupi'
            ],200);
        }

        $data['harga'] = $data_produk->harga;

        Keranjang::create($data);

        return response()->json([
            'status'=>TRUE,
            'msg'=>'data berhasil ditambahkan'
        ],201);
    }

    function get_cart(Request $request)
    {
        $username = $request->input('username');
        $keranjang = Keranjang::where('username',$username)->get();

        if($keranjang->isEmpty()){
            return response()->json([
                'status'=>FALSE,
                'msg'=>'Cart Kosong'
            ],200);
        }
        else
        {
            return KeranjangResource::collection($keranjang);
        }


    }
}
