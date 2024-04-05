<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Institution;
use App\Models\Section;
use App\Models\Date;
use Illuminate\Support\Facades\DB;

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
        return view('admin.new_dates.new-dates', compact('institutions'));
        
    }

    public function showRiportsPage(){
        $institutions = Institution::all();
        return view('admin.riports.riports',compact('institutions'));
    }

    public function getSections(Request $request)
    {
        $institutionId = $request->institution_id;

        $sections = Section::whereHas('institutions', function ($query) use ($institutionId) {
            $query->where('institution_id', $institutionId);
        })->get();
       
        return response()->json($sections);
    }

    public function getDoctors(Request $request)
    {
        $institutionId = $request->institution_id;
        $sectionId = $request->section_id;
        $sectionInstitutionId = DB::table('sections_in_institutions')
        ->where('section_id', $sectionId)
        ->where('institution_id', $institutionId)
        ->value('id');

    
    $users = User::where('role', 'doctor')
         ->where('section_in_institution_id', $sectionInstitutionId)
         ->get();

        return response()->json($users);
    }

    public function insertDates(Request $request)
    {
        $date = new Date;
        $date->user_id = $request->doctor_id;
        $date->section_id = $request->section_id;
        $date->start_time = $request->start_time;
        $date->end_time = $request->end_time;
        $date->save();

        return response()->json(['success' => true]);
    }
    

}
