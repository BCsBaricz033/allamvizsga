<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function showRiportsPage()
    {
        return view('doctor.riports');
    }
    public function showNewDatesPage()
    {
        return view('doctor.new-dates');
    }
}
