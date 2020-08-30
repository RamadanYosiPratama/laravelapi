<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\AgenResource;
use App\Agen;
use Validator;
use Storage;

class AgenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return AgenResource::collection(Agen::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Htp\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input,[
            'store_name'=>'required|max:255',
            'store_owner'=>'required|max:255',
            'address'=>'required|max:255',
            'latitude'=>'required|max:255',
            'longitude'=>'required|max:255',
            'photo_store'=>'required|image|mimes:jpeg,jpg,png|max:2048'
        ]);

        if($validator->fails())
        {
            return response()->json([
                "status"=>FALSE,
                "msg"=>$validator->errors()
            ],404);
        }

        if($request->file('photo_store')->isValid()){
            $photo_store = $request->file('photo_store');
            $extension = $photo_store->getClientOriginalExtension();
            $namaFoto = "agen/".date("YmdHis").".".$extension;
            $upload_path = 'public/uploads/agen';
            $request->file('photo_store')->move($upload_path,$namaFoto);
            $input['photo_store'] = $namaFoto;
        }

        if(Agen::create($input)){
            // memberikan response berhasil
            return response()->json([
                'status'=>TRUE,
                'msg'=>'Agen Berhasil Disimpan'
            ],201);
        }
        else
        {
            // response gagal
            return response()->json([
                'status'=>FALSE,
                'msg'=>'Agen Gagal Disimpan'
            ],200);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $agen = Agen::find($id);
        if(is_null($agen)){
            return response()->json([
                "status"=>FALSE,
                "msg"=>'Record Not Found'
            ],404);
        }
        return new AgenResource($agen);
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
        $input = $request->all();
        $agen = Agen::find($id);
        if(is_null($agen))
        {
            return response()->json([
                'status'=>FALSE,
                'msg'=>'Record Not Found' 
            ],404);
        }

        
        $validator = Validator::make($input,[
            'store_name'=>'required|max:255',
            'store_owner'=>'required|max:255',
            'address'=>'required|max:255',
            'latitude'=>'required|max:255',
            'longitude'=>'required|max:255',
            'photo_store'=>'sometimes|image|mimes:jpeg,jpg,png|max:2048'
        ]);

        if($validator->fails())
        {
            return response()->json([
                "status"=>FALSE,
                "msg"=>$validator->errors()
            ],404);
        }

        if($request->hasFile('photo_store'))
        {
            if($request->file('photo_store')->isValid())
            {
                Storage::disk('upload')->delete($agen->photo_store);

                $photo_store = $request->file('photo_store');
                $extension = $photo_store->getClientOriginalExtension();
                $namaFoto = "agen/".date('YmdHis').".".$extension;
                $upload_path = 'public/uploads/agen';
                $request->file('photo_store')->move($upload_path,$namaFoto);
                $input['photo_store'] = $namaFoto;
            }
        }
        $agen->update($input);
        return response()->json([
            'status'=>FALSE,
            'msg'=>'Data Berhasil diupdate'
        ],200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $agen = Agen::find($id);
        if(is_null($agen))
        {
            return response()->json([
                'status'=>FALSE,
                'msg'=>'Record Not found',
            ],404);
        }
        $agen->delete();
        Storage::disk('upload')->delete($agen->photo_store);
        return response()->json([
            'status'=>TRUE,
            'msg'=>'Data Berhasil Disimpan',
        ],200);
    }

    public function search(Request $request)
    {
        $filterKeyword = $request->get('keyword');
        return AgenResource::collection(Agen::where('store_name','LIKE',"%$filterKeyword%")->get());
    }
}
