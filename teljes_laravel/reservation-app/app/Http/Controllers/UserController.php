<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function showOwnDatesPage()
    {
        return view('user.own-dates');
    }
    public function showDateReservationPage()
    {
        return view('user.date-reservation');
    }
}
