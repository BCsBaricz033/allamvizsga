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
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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


    public function showRiportsPage(Request $request)
    {
        $institutions = Institution::orderBy('name')->get();
        $doctors = User::where('role', 'doctor')->orderBy('name')->get();
        $sections = Section::orderBy('name')->get();
        $patients = User::join('dates', 'users.id', '=', 'dates.patient_id')
            ->select('users.*')
            ->distinct()
            ->orderBy('name')
            ->get();

        // getting filtered dates

        $institutionIds = $request->input('institutionIds', null);
        $sectionIds = $request->input('sectionIds', null);
        $doctorIds = $request->input('doctorIds', null);
        $patientIds = $request->input('patientIds', null);
        $from = $request->input('from', null);
        $to = $request->input('to', null);
        $reserved = $request->input('reserved', null);
        $filter = $request->input('filter', false);
        $filteredDates = Date::query();
        if ($institutionIds) {

            $selectedInstitutionIdsInSectionsInInstitutionsTable = DB::table('sections_in_institutions')
                ->whereIn('institution_id', $institutionIds)
                ->pluck('id')
                ->toArray();

            $filteredDates->whereIn('dates.section_in_institution_id', $selectedInstitutionIdsInSectionsInInstitutionsTable);
        }

        if ($sectionIds) {
            $selectedSectionIdsInSectionsInInstitutionsTable = DB::table('sections_in_institutions')
                ->whereIn('section_id', $sectionIds)
                ->pluck('id')
                ->toArray();

            $filteredDates->whereIn('dates.section_in_institution_id', $selectedSectionIdsInSectionsInInstitutionsTable);
        }
        if ($doctorIds) {

            $filteredDates->whereIn('doctor_id', $doctorIds);
        }

        if ($patientIds) {
            $filteredDates->whereIn('patient_id', $patientIds);
        }

        if ($from) {
            $from=new DateTime($from);
            $filteredDates->whereDate('end_time', '>=', $from);
        }
        if ($to) {
            $to=new DateTime($to);
            $filteredDates->whereDate('end_time', '<=', $to);
        }

        if ($reserved == 1) {
            $filteredDates->where('reserved', true);
        }
        if($filter==false){
            $today = Carbon::today();
            $filteredDates->whereDate('start_time', '=', $today);
        }
        $filteredDates = $filteredDates->join('users as doctors', 'dates.doctor_id', '=', 'doctors.id')
            ->leftJoin('users as patients', 'dates.patient_id', '=', 'patients.id')
            ->join('sections_in_institutions', 'dates.section_in_institution_id', '=', 'sections_in_institutions.id')
            ->join('institutions', 'sections_in_institutions.institution_id', '=', 'institutions.id')
            ->join('sections', 'sections_in_institutions.section_id', '=', 'sections.id')
            ->select([
                'dates.id',
                'doctors.name as doctor_name',
                'patients.name as patient_name',
                'dates.start_time',
                'dates.end_time',
                'dates.reserved',
                'dates.reason',
                'dates.cnp',
                'dates.birthdate',
                'dates.phone',
                'institutions.name as institution_name',
                'sections.name as section_name',

            ]);

        $filteredDates = $filteredDates->orderBy('start_time', 'desc')->paginate(10)->withQueryString();
        return Inertia::render('Admin/Riports', [
            'institutions' => $institutions,
            'sections' => $sections,
            'patients' => $patients,
            'doctors' => $doctors,
            'dates' => $filteredDates,
        ]);
    }
}
