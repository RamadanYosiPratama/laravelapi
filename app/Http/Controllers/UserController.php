<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Validator;
use Illuminate\Support\Arr;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // untuk menampilkan pagination 5 record
        // $user = User::paginate(5);
        // return view('user.index',compact('user'));
        $user = User::paginate(5);
        $filterKeyword = $request->get('keyword');
        if($filterKeyword)
        {
            //dijalankan jika ada pencarian
            $user = User::where('name','LIKE',"%$filterKeyword%")->paginate(5);
        }
        return view('user.index',compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // biar nuju ke halaman create
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validasi = Validator::make($data,[
            'name'=>'required|max:255',
            'email'=>'required|max:255|unique:users',
            'username'=>'required|max:100|unique:users',
            'password'=>'required|min:6',
            'level'=>'required' 
        ]);

        if($validasi->fails())
        {
            return redirect()->route('user.create')->withInput()->withErrors($validasi);
        }
        $data['password'] = bcrypt($data['password']);
        User::create($data);
        return redirect()->route('user.index')->with('status','User Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('user.edit', compact('user'));
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
        $user = User::findOrfail($id);
        $data = $request->all();

        $validasi = Validator::make($data,[
            'name'=>'required|max:255',
            'username'=>'required|max:100|unique:users,username,'.$id,
            'email'=>'required|email|max:255|unique:users,email,'.$id,
            'password'=>'sometimes|nullable|min:6'
            // untuk validasi kalo mengisi password boleh engga yaudah pake password sebelumnya
        ]);

        if($validasi->fails()){
            return redirect()->route('users.edit',[$id])->withErrors($validasi);
        }
        if($request->input('password'))
        {
            $data['password'] = bcrypt($data['password']);
        }
        else
        {
            $data = Arr::except($data,['password']);
            // menghapus array data element password    
            // biar kalo di update bagian password ga ikut ke update
        }
        $user->update($data);
        return redirect()->route('user.index')->with('status','User Berhasil Di Update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = User::findOrFail($id);
        $data->delete();
        return redirect()->route('user.index')->with('status','User Berhasil di delete');
    }
}
