<?php

namespace App\Http\Controllers;


use App\Models\Date;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Institution;
use App\Models\Section;
use Inertia\Inertia;
use DateTime;
use DateInterval;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class UserController extends Controller
{
    public function showDashboard()
    {
        
        
        $institutions = Institution::orderBy('name')->get();
        $doctors = User::where('role', 'doctor')->orderBy('name')->get();
        $sections = Section::orderBy('name')->get();
        $patients=[auth()->user()];
        return Inertia::render('User/Dashboard', [
            'institutions' => $institutions,
            'sections' => $sections,
            'patients' => $patients,
            'doctors'=> $doctors
            
        ]);
    }
    public function showDateReservationPage()
    {
        $institutions = Institution::orderBy('name')->get();
        $doctors = User::where('role', 'doctor')->orderBy('name')->get();
        $sections = Section::orderBy('name')->get();
        $user=auth()->user();
        return Inertia::render('User/Reserve', [
            'institutions' => $institutions,
            'sections' => $sections,
            'doctors'=> $doctors,
            'user'=>$user
        ]);
    }

    public function showReservationForm(Request $request)
    {
        $user = Auth::user();
        $date = Date::where('id', $request->appointment_id)->first();
        $doctor = $request->doctor_name;
        return view('user.reserve', compact('date', 'user','doctor'));
    }
    public function showReservationPageWithoutLogin(){
        $institutions = Institution::orderBy('name')->get();
        $doctors = User::where('role', 'doctor')->orderBy('name')->get();
        $sections = Section::orderBy('name')->get();
        return Inertia::render('ReserveWithoutLogin', [
            'institutions' => $institutions,
            'sections' => $sections,
            'doctors'=> $doctors,
        ]);
    }
    public function showReservationFormWithoutLogin(Request $request)
    {
        
        $date = Date::where('id', $request->appointment_id)->first();
        $doctor = $request->doctor_name;
        return view('reserve-without-login-form', compact('date','doctor'));
    }
    public function cancelDate(Request $request)
    {
        //Log::info('Request received for cancelDate:'. $request->id);
        $date = Date::findOrFail($request->id);
        $date->update([
            'patient_id' => null,
            'reserved' => false,
            'reason' => null,
            'cnp' => null,
            'birthdate' => null,
            'phone' => null,
        ]);
    
        return response()->json(['success' => 'Date cancelled successfully.']);
    }
}
