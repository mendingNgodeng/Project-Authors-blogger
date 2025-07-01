<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UsersRequestAdd as dataReq; // data validate input data

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth; //Auth
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash; //password hash
use Illuminate\Support\Facades\Session; //sesssion
use Illuminate\Support\Facades\Storage; //storage
use Illuminate\Support\Facades\Validator; //validator i guess
use Illuminate\View\View;

class UsersController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role;
        $id_user = Auth::user()->_id;
        $poster = Auth::user()->name;

        
        $user = User::all();
        return view('blog.users.index',compact('user','poster','id_user','role'));
    }

    public function api()
{
    return User::all();
}

public function show($id)
{
    return response()->json(User::findOrFail($id));
}

    public function create()
    {
        return view('blog.users.create');
    }

    public function store(dataReq $req)
    {
        $data = $req->validated();
          $password = empty($data['password']) ? $data['username'] : $data['password'];

      

        if($req->hasFile('pic'))
        {
            $file = $req->file('pic');
            $filename = time().'_'.$file->getClientOriginalName();
            $path=$file->storeAs('users',$filename,'public');
            
            // save ke DB
            $data['pic'] = '/storage/'.$path;
        }

          User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'pic' => $data['pic'],
            'role' => 'user'
        ]);

        // return to_route('users.index');
         return response()->json(['message' => 'User Created']);
    }

    public function detail($id)
    {
        $data = User::findOrFail($id);
        return view('blog.users.detail', compact('data'));
    }

    public function edit($id)
    {
        $data = User::findOrFail($id);
        return view('blog.users.edit', compact('data'));
    }

    public function update(dataReq $req,$id)
    {
        $user = User::findOrFail($id);

        $data = $req->validated();
        
        $user->name = $data['name'];
        $user->username = $data['username'];
        $user->email = $data['email'];
        
        // upload file
         if($req->hasFile('pic'))
        {

        if ($user->pic && Storage::disk('public')->exists(str_replace('/storage/', '', $user->pic))) {
        Storage::disk('public')->delete(str_replace('/storage/', '', $user->pic));
        }

            $file = $req->file('pic');
            $filename = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('users',$filename,'public');

            $data['pic'] = '/storage/'.$path;
        }else{
            // take the previous insert file
            $data['pic'] = $user->pic;
        }
         // set updated pic
        $user->pic = $data['pic'];

        // only update if password input is filled
        if(!empty($data['password']))
        {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        // return to_route('users.index');
         return response()->json(['message' => 'User updated']);
    }

    public function delete(Request $req,$id)
    {
        $user = User::findOrFail($id);
    if ($user->pic && file_exists(public_path($user->pic))) {
        unlink(public_path($user->pic));
    }
    $user->delete();
    return response()->json(['message' => 'User deleted']);
        // return to_route('users.index');
    }
    // ajax
      public function storeAjax(dataReq $req)
    {
        $data = $req->validated();
          $password = empty($data['password']) ? $data['username'] : $data['password'];

    //   switch into the request instead
        //   if ($data->fails()) {
        //     return response()->json($data->errors(), 422);
        // }
        $data['pic'] = null;
        if($req->hasFile('pic'))
        {
            $file = $req->file('pic');
            $filename = time().'_'.$file->getClientOriginalName();
            $path=$file->storeAs('users',$filename,'public');
            
            // save ke DB
            $data['pic'] = '/storage/'.$path;
        }

          $data_users = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']) ?? Hash::make($password),
            'pic' => $data['pic'],
            'role' => $data['role'] ?? 'user',
            'status' => ''
        ]);

       
        // return to_route('users.index');
         return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Disimpan!',
            'data'    => $data_users  
        ]);
    }

}
