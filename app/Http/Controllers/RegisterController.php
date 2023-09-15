<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'name'=>'required|max:55',
            'email'=>'required|max:55|unique:users,email',
            'password'=>'required|min:6|max:12'
        ]);
    }
}
