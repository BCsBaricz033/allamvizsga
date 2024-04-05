<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AssistantController extends Controller
{
    public function showRiportsPage()
    {
        return view('assistant.riports');
    }
    public function showNewDatesPage()
    {
        return view('assistant.new-dates');
    }
}
