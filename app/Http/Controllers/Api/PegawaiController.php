<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Pegawai;
use Validator;
use App\Http\Resources\PegawaiResource;

class PegawaiController extends Controller
{
    public function login_pegawai(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input,[
            'username'=>'required',
            'password'=>'required'
        ]);
        if($validator->fails())
        {
            return response()->json([
                'status'=>FALSE,
                'msg'=>$validator->errors()
            ],400);
        }

        $username = $request->input('username');
        $password = $request->input('password');

        $pegawai = Pegawai::where([
            ['username',$username],
            ['is_aktif',1]
        ])->first();

        if(is_null($pegawai))
        {
            // Jika Pegawai Tidak Ditemukan
            return response()->json([
                'status'=>FALSE,
                'msg'=>'tidak ditemukan',
            ],200);
        }
        else
        {
            // jika pegawai ditemukan
            if(password_verify($password,$pegawai->password))
            {
                // jika password sesuai
                return response()->json([
                    'status'=>TRUE,
                    'msg'=>'Password Sesuai',
                    'pegawai'=>new PegawaiResource($pegawai)
                ],200);
            }
            else
            {
                // jika password ga sesuai
                return response()->json([
                    'status'=>FALSE,
                    'msg'=>'username dan password tidak sesuai',

                ],200);
            }
        }
    }
}
