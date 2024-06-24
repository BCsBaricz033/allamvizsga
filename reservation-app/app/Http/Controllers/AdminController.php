<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Institution;
use App\Models\Section;
use App\Models\Date;
use DateTime;
use DateInterval;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {

        $search = $request->input('search', '');

        $users = User::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/Dashboard', [
            'users' => $users,
            'filters' => $search
        ]);
    }
    public function filterUsers(Request $request)
    {
        $users = User::query()
            ->when(Request::input('search'), function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            })
            ->orderBy('name')
            ->paginate(3);
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->update($request->all());
    }

    public function destroyUser(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->delete();
    }

    public function showNewDatesPage()
    {
        $institutions = Institution::orderBy('name')->get();
        $doctors = User::where('role', 'doctor')->orderBy('name')->get();
        $sections = Section::orderBy('name')->get();
        
        return response()->json([
            'institutions' => $institutions,
            'doctors' => $doctors,
            'sections' => $sections,
        ]);
        
        
    }


    public function showRiportsPage()
    {
        $institutions = Institution::orderBy('name')->get();
        $doctors = User::where('role', 'doctor')->orderBy('name')->get();
        $sections = Section::orderBy('name')->get();
        $patients = User::join('dates', 'users.id', '=', 'dates.patient_id')
            ->select('users.*')
            ->distinct()
            ->orderBy('name')
            ->get();
        return Inertia::render('Admin/Riports', [
            'institutions' => $institutions,
            'sections' => $sections,
            'patients' => $patients,
            'doctors'=> $doctors
        ]);
    }
}
