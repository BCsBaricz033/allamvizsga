<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Institution;
class AdminController extends Controller
{
    public function users()
    {
        $users = User::latest()->paginate(3);
        return view('admin.users.index', compact('users'));
    }
    
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());
        return redirect()->route('admin.users.index');
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.users.index');
    }

    public function showNewDatesPage()
    {   $institutions = Institution::all();
        return view('admin.new-dates', compact('institutions'));
        
    }

    public function showRiportsPage(){
        $institutions = Institution::all();
        return view('admin.riports',compact('institutions'));
    }
    

}
