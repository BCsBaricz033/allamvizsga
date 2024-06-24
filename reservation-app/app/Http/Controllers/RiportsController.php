<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Institution;
use App\Models\Section;
use App\Models\Date;
use DateTime;
use DateInterval;
use Illuminate\Support\Facades\Arr;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RiportsController extends Controller
{
    public function getSections(Request $request)
    {
        $institutionIds = $request->institutionIds;
        $sections = Section::whereHas('institutions', function ($query) use ($institutionIds) {
            $query->whereIn('institution_id', $institutionIds);
        })->get();

        return response()->json($sections);
    }


    public function getDoctors(Request $request)
    {
        $institutionIds = $request->institutionIds ? $request->institutionIds : null;
        $sectionIds = $request->sectionIds ? $request->sectionIds : null;
        // Fetch section_in_institution_ids for the given institution and section IDs
        if ($institutionIds && $sectionIds) {
            $sectionInstitutionIds = DB::table('sections_in_institutions')
                ->whereIn('section_id', $sectionIds)
                ->whereIn('institution_id', $institutionIds)
                ->pluck('id');
        } elseif (!$institutionIds && $sectionIds) {
            $sectionInstitutionIds = DB::table('sections_in_institutions')
                ->whereIn('section_id', $sectionIds)
                ->pluck('id');
        } elseif ($institutionIds && !$sectionIds) {
            $sectionInstitutionIds = DB::table('sections_in_institutions')
                ->whereIn('institution_id', $institutionIds)
                ->pluck('id');
        } else {
            $sectionInstitutionIds = DB::table('sections_in_institutions')
                ->pluck('id');
        }



        // Fetch doctors associated with the fetched section_in_institution_ids
        $doctors = User::where('role', 'doctor')
            ->whereIn('section_in_institution_id', $sectionInstitutionIds)
            ->get();

        return response()->json($doctors);
    }
    public function getPatients(Request $request)
    {
        $institutionIds = $request->institutionIds ? $request->institutionIds : null;
        $sectionIds = $request->sectionIds ? $request->sectionIds : null;
        $doctorIds = $request->doctorIds ? $request->doctorIds : null;

        if ($institutionIds && $sectionIds) {
            $sectionInstitutionIds = DB::table('sections_in_institutions')
                ->whereIn('section_id', $sectionIds)
                ->whereIn('institution_id', $institutionIds)
                ->pluck('id');
        } elseif (!$institutionIds && $sectionIds) {
            $sectionInstitutionIds = DB::table('sections_in_institutions')
                ->whereIn('section_id', $sectionIds)
                ->pluck('id');
        } elseif ($institutionIds && !$sectionIds) {
            $sectionInstitutionIds = DB::table('sections_in_institutions')
                ->whereIn('institution_id', $institutionIds)
                ->pluck('id');
        } else {
            $sectionInstitutionIds = DB::table('sections_in_institutions')
                ->pluck('id');
        }

        $patients = User::join('dates', 'users.id', '=', 'dates.patient_id')
            ->whereIn('dates.section_in_institution_id', $sectionInstitutionIds);
        
        if ($doctorIds) {
            $patients = $patients->whereIn('dates.doctor_id', $doctorIds);
        }

        $patients = $patients->orderBy('name')->get();
        return response()->json($patients);
    }



    public function getFilteredDates(Request $request)
    {
        $institutionIds = $request->institutionIds ? $request->institutionIds : null;
        $sectionIds = $request->sectionIds ? $request->sectionIds : null;
        $doctorIds = $request->doctorIds ? $request->doctorIds : null;
        $patientIds = $request->patientIds ? $request->patientIds : null;
        $from = $request->from ? new DateTime($request->from) : null;
        $to = $request->to ? new DateTime($request->to) : null;
        $reserved = $request->reserved ? $request->reserved : null;
        $without_login = $request->without_login ? $request->without_login : null;
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

        if ($request->has('patientIds')) {
            $patientIds = $request->patientIds;
            $filteredDates->whereIn('patient_id', $patientIds);
        }

        if ($from) {
            $filteredDates->where('end_time', '>=', $from);
        }
        if ($to) {
            $filteredDates->where('end_time', '<=', $to);
        }

        if ($reserved == 1) {
            $filteredDates->where('reserved', true);
        }
        if ($without_login == true) {
            $filteredDates->where('reserved', false);
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

        $filteredDates = $filteredDates->paginate(10);

        return response()->json($filteredDates);
    }
    public function getFilteredDatesForReservation(Request $request)
    {
        $institutionIds = $request->institutionIds ? $request->institutionIds : null;
        $sectionIds = $request->sectionIds ? $request->sectionIds : null;
        $doctorIds = $request->doctorIds ? $request->doctorIds : null;
        $patientIds = $request->patientIds ? $request->patientIds : null;
        $from = $request->from ? new DateTime($request->from) : null;
        $to = $request->to ? new DateTime($request->to) : null;
        $reserved = $request->reserved ? $request->reserved : null;
        $without_login = $request->without_login ? $request->without_login : null;
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

        if ($request->has('patientIds')) {
            $patientIds = $request->patientIds;
            $filteredDates->whereIn('patient_id', $patientIds);
        }

        if ($from) {
            $filteredDates->where('end_time', '>=', $from);
        }
        if ($to) {
            $filteredDates->where('end_time', '<=', $to);
        }

        
        $filteredDates->where('reserved',false);
        if ($without_login == true) {
            $filteredDates->where('reserved', false);
        }

        $today = Carbon::today();
        $filteredDates = $filteredDates->join('users as doctors', 'dates.doctor_id', '=', 'doctors.id')
            ->leftJoin('users as patients', 'dates.patient_id', '=', 'patients.id')
            ->join('sections_in_institutions', 'dates.section_in_institution_id', '=', 'sections_in_institutions.id')
            ->join('institutions', 'sections_in_institutions.institution_id', '=', 'institutions.id')
            ->join('sections', 'sections_in_institutions.section_id', '=', 'sections.id')
            ->whereDate('dates.start_time', '>', $today)
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

        $filteredDates = $filteredDates->paginate(10);

        return response()->json($filteredDates);
    }
    public function destroyDate(Request $request)
    {
        $date = Date::findOrFail($request->id);
        $date->delete();
    }
}
