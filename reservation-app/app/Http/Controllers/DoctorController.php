<?php

namespace App\Http\Controllers;

use App\Models\Date;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Institution;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Carbon\Carbon;

class DoctorController extends Controller
{
    public function showRiportsPage()
    {
        $userSectionId = auth()->user()->section_in_institution_id;
        $institutionIds = DB::table('sections_in_institutions')
            ->where('id', $userSectionId)
            ->pluck('institution_id')
            ->toArray();

        $sectionIds = DB::table('sections_in_institutions')
            ->where('id', $userSectionId)
            ->pluck('section_id')
            ->toArray();


        $institutions = DB::table('institutions')
            ->whereIn('id', $institutionIds)
            ->get();
        $sectionInstitutionIds = DB::table('sections_in_institutions')
            ->whereIn('institution_id', $institutionIds)
            ->whereIn('section_id', $sectionIds)
            ->pluck('id')
            ->toArray();


        if (!auth()->user()->head) {
            $sections = DB::table('sections')
                ->whereIn('id', $sectionIds)
                ->get();
            $doctors = [auth()->user()];
            $doctorIds = [auth()->user()->id];
        } else {
            $sections = DB::table('sections')
                ->join('sections_in_institutions', 'sections_in_institutions.section_id', '=', 'sections.id')
                ->whereIn('sections_in_institutions.institution_id', $institutionIds)
                ->select('sections.*')
                ->get();
            $doctors = User::join('sections_in_institutions', 'sections_in_institutions.id', '=', 'users.section_in_institution_id')
                ->whereIn('sections_in_institutions.id', $sectionInstitutionIds)
                ->where('role', 'doctor')
                ->select([
                    'users.id',
                    'users.name'
                ])
                ->orderBy('name')
                ->get();
            $doctorIds = User::join('sections_in_institutions', 'sections_in_institutions.id', '=', 'users.section_in_institution_id')
                ->whereIn('sections_in_institutions.id', $sectionInstitutionIds)
                ->where('role', 'doctor')
                ->select([
                    'users.id'
                ])
                ->orderBy('name')
                ->get();
        }






        $patients = User::join('dates', 'users.id', '=', 'dates.patient_id')
            ->whereIn('dates.section_in_institution_id', $sectionInstitutionIds)
            ->whereIn('dates.doctor_id', $doctorIds)
            ->select([
                'users.id',
                'users.name'
            ])
            ->orderBy('name')
            ->get();
        $today = Carbon::today();
        $dates = Date::join('users as doctors', 'dates.doctor_id', '=', 'doctors.id')
            ->leftJoin('users as patients', 'dates.patient_id', '=', 'patients.id')
            ->join('sections_in_institutions', 'dates.section_in_institution_id', '=', 'sections_in_institutions.id')
            ->join('institutions', 'sections_in_institutions.institution_id', '=', 'institutions.id')
            ->join('sections', 'sections_in_institutions.section_id', '=', 'sections.id')
            ->whereIn('dates.section_in_institution_id', $sectionInstitutionIds)
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

            ])
            ->where('dates.doctor_id', auth()->user()->id)
            ->whereDate('dates.start_time', '=', $today)
            ->paginate(10);
        $userSection = DB::table('sections')
            ->whereIn('id', $sectionIds)
            ->get();
        return Inertia::render('Doctor/Dashboard', [
            'institutions' => $institutions,
            'sections' => $sections,
            'patients' => $patients,
            'doctors' => $doctors,
            'dates' => $dates,
            'user' => auth()->user(),
            'userSection'=>$userSection
        ]);
    }


    public function showNewDatesPage()
    {
        $userSectionId = auth()->user()->section_in_institution_id;
        $institutionIds = DB::table('sections_in_institutions')
            ->where('id', $userSectionId)
            ->pluck('institution_id')
            ->toArray();

        $sectionIds = DB::table('sections_in_institutions')
            ->where('id', $userSectionId)
            ->pluck('section_id')
            ->toArray();


        $institutions = DB::table('institutions')
            ->whereIn('id', $institutionIds)
            ->get();
        $sectionInstitutionIds = DB::table('sections_in_institutions')
            ->whereIn('institution_id', $institutionIds)
            ->whereIn('section_id', $sectionIds)
            ->pluck('id')
            ->toArray();


        if (!auth()->user()->head) {
            $sections = DB::table('sections')
                ->whereIn('id', $sectionIds)
                ->get();
            $doctors = [auth()->user()];
        } else {

            $sections = DB::table('sections')
                ->join('sections_in_institutions', 'sections_in_institutions.section_id', '=', 'sections.id')
                ->whereIn('sections_in_institutions.institution_id', $institutionIds)
                ->select('sections.*')
                ->get();
            $doctors = User::join('sections_in_institutions', 'sections_in_institutions.id', '=', 'users.section_in_institution_id')
                ->whereIn('sections_in_institutions.id', $sectionInstitutionIds)
                ->where('role', 'doctor')
                ->orderBy('name')
                ->get();
        }


        return response()->json([
            'institutions' => $institutions,
            'doctors' => $doctors,
            'sections' => $sections,
            'head' => auth()->user()->head ? true : false,
        ]);
    }
}
