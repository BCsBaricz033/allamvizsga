<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class adminController extends Controller
{
    public function index()
    {
    $users = User::where('is_active', 1)->get();
    return response()->json($users);
    }


    public function store(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->phone = $request->phone;
        $user->adress = $request->address;
        $user->cnp = $request->cnp;
        $user->birthdate = $request->birthdate;
        $user->role = $request->role;
        $user->save();
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->adress = $request->address;
        $user->cnp = $request->cnp;
        $user->birthdate = $request->birthdate;
        $user->role = $request->role;

        $user->save();
        return response()->json($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = 0;
        $user->save();
        return response()->json('User deleted successfully');
    }
}
