<?php

namespace App\Http\Controllers;
use App\Http\Resources\UserResource;
use App\Models\User;

use Illuminate\Http\Request;

class UsermanagerController extends Controller
{
    public function create(Request $request){
        $request->validate([
            'name'=> 'required',
            'email'=> 'required',
            'phone_no'=> 'required',
            'role'=> 'required|in:admin,librarian,member',
        ]);

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'phone_no'=>$request->phone_no,
            'role'=>$request->role,
            'password'=>$request->password
        ]);

        return UserResource::make($user)->additional([
            'message'=> 'User record created successfully',
            'status'=> 'Success'
        ]);
    }
}
