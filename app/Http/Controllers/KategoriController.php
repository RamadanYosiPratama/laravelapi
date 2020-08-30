<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Kategori;
use Validator;
use Storage;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $kategori = Kategori::paginate(5);
        $filterKeyword = $request->get('keyword');
        if($filterKeyword)
        {
            $kategori = Kategori::where('kategori','LIKE',"%$filterKeyword%")->paginate(5);
        }
        return view('kategori.index',compact('kategori'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('kategori.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'kategori'=>'required|max:355',
            'gambar_kategori'=>'required|image|mimes:jpeg,jpg,png|max:2048'
        ]);

        if($validator->fails())
        {
            return redirect()->route('kategori.create')->withErrors($validator)->withInput();
        }

        $gambar_kategori = $request->file('gambar_kategori');
        $extension = $gambar_kategori->getClientOriginalExtension();

        if($request->file('gambar_kategori')->isValid())
        {
            $namaFoto = "kategori/".date('YmdHis').".".$extension;
            $upload_path = 'public/uploads/kategori';
            $request->file('gambar_kategori')->move($upload_path, $namaFoto);
            // untuk mindahin ke form
            $input['gambar_kategori'] = $namaFoto;
            // untuk simpan di database
        }
        // insert data ke database 
        Kategori::create($input);
        return redirect()->route('kategori.index')->with('status','berhasil');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $kategori = Kategori::findOrFail($id);
        return view('kategori.edit', compact('kategori'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $kategori = kategori::findOrFail($id);

        $input = $request->all();

        $validator = Validator::make($input , [
            'kategori'=>'required|max:255',
            'gambar_kategori'=>'sometimes|nullable|image|mimes:jpeg,jpg,png|max:2048'
        ]);
        if($validator->fails())
        {
            return redirect()->route('kategori.edit', [$id])->withErrors('$validator');
        }

        // ngecek ada atau tidak kalo ada yang sebelumna dihapus
        if($request->hasFile('gambar_kategori')){
            // cek gambar baru valid atau ga
            if($request->file('gambar_kategori')->isValid())
            {
                Storage::disk('upload')->delete($kategori->gambar_kategori);
                // akses untuk folder upload yang ada di filesystem.php tambahin array di filesystem.php 
                
                // upload file yang baru 
                $gambar_kategori = $request->file('gambar_kategori');
                $extension = $gambar_kategori->getClientOriginalExtension();
                // untuk menangkap file extension
                $namaFoto = "kategori/".date('YmdHis').".".$extension;
                $upload_path = 'public/uploads/kategori';
                $request->file('gambar_kategori')->move($upload_path, $namaFoto);
                // untuk mindahin ke form
                $input['gambar_kategori'] = $namaFoto;
                // untuk simpan di database

            }
        }
        $kategori->update($input);
        return redirect()->route('kategori.index')->with('status','berhasil update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Kategori::findOrFail($id);
        $data->delete();
        return redirect()->route('kategori.index')->with('status', 'Berhasil di delete');

    }
}
