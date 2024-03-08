<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
       $input = $request->all();

       User::create([
        'name' => $input['name'],
        'email' => $input['email'],
        'password' => Hash::make($input['password'])
      ]);

          return response()->json(['status' => true,
                                    'message' => "Registation Success"   
        
        ]);
    }

    public function check(Request $request)
    {

     $credentials = $request->validate([
     'email' => ['required', 'email'],
     'password' => ['required'],
        ]);
        
        if (Auth::attempt($credentials)) 
        {
           return response()->json([ 'status' => true ,
                                     'message' => "Success"
        ]);
        }
            return response()->json(['status' => false ,
                                     'message' => "Fail"
        
        ]);
    }
}
