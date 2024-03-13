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
        'password' => Hash::make($input['password']),
        'phone'=> $input['phone'],
        'adress'=> $input['adress'],
        'birthdate'=> $input['birthdate'],
        'cnp'=> $input['cnp'],
        'is_active'=> 1
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
            
            $user = Auth::user();
            Auth::login($user);
           return response()->json([ 'status' => true ,
                                     'message' => "Success",
                                     'name' => $user->name,
                                     'role' => $user->role,
                                     'token' => $user->createToken('reservation-app')->plainTextToken
        ]);
        }
            return response()->json(['status' => false ,
                                     'message' => "Fail"
        
        ]);
    }
    public function checkLoggedIn() {
        //return Auth::check();
        
        if(Auth::check()){
            return response()->json(['status' => true,
                                    'message' => "logged"
                                ]);
        }
        else{
            return response()->json(['status' => false,
                                    'message' => "Fail"
                                ]);
        }
        
    }
    
    
    
}
