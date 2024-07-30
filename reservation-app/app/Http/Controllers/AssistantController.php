<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Date;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Institution;
use App\Models\Section;
use App\Models\User;

class AssistantController extends Controller
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


        if (!auth()->user()->head) {
            $sections = DB::table('sections')
                ->whereIn('id', $sectionIds)
                ->get();
        } else {
            $sections = DB::table('sections')
                ->join('sections_in_institutions', 'sections_in_institutions.section_id', '=', 'sections.id')
                ->whereIn('sections_in_institutions.institution_id', $institutionIds)
                ->select('sections.*')
                ->get();
        }


        $sectionInstitutionIds = DB::table('sections_in_institutions')
            ->whereIn('institution_id', $institutionIds)
            ->whereIn('section_id', $sectionIds)
            ->pluck('id')
            ->toArray();


        $doctors = User::join('sections_in_institutions', 'sections_in_institutions.id', '=', 'users.section_in_institution_id')
            ->whereIn('sections_in_institutions.id', $sectionInstitutionIds)
            ->where('role', 'doctor')
            ->select([
                'users.id',
                'users.name'
            ])
            ->orderBy('name')
            ->distinct()
            ->get();

        $doctorIds = User::join('sections_in_institutions', 'sections_in_institutions.id', '=', 'users.section_in_institution_id')
            ->whereIn('sections_in_institutions.id', $sectionInstitutionIds)
            ->where('role', 'doctor')
            ->select([
                'users.id',
            ])
            ->orderBy('name')
            ->get();

        $patients = User::join('dates', 'users.id', '=', 'dates.patient_id')
            ->whereIn('dates.section_in_institution_id', $sectionInstitutionIds)
            ->whereIn('dates.doctor_id', $doctorIds)
            ->select([
                'users.id',
                'users.name'
            ])
            ->distinct()
            ->orderBy('name')
            ->get();


        return Inertia::render('Assistant/Dashboard', [
            'institutions' => $institutions,
            'sections' => $sections,
            'patients' => $patients,
            'doctors' => $doctors
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


        if (!auth()->user()->head) {
            $sections = DB::table('sections')
                ->whereIn('id', $sectionIds)
                ->get();
        } else {
            $sections = DB::table('sections')
                ->join('sections_in_institutions', 'sections_in_institutions.section_id', '=', 'sections.id')
                ->whereIn('sections_in_institutions.institution_id', $institutionIds)
                ->select('sections.*')
                ->get();
        }


        $sectionInstitutionIds = DB::table('sections_in_institutions')
            ->whereIn('institution_id', $institutionIds)
            ->whereIn('section_id', $sectionIds)
            ->pluck('id')
            ->toArray();


        $doctors = User::join('sections_in_institutions', 'sections_in_institutions.id', '=', 'users.section_in_institution_id')
            ->whereIn('sections_in_institutions.id', $sectionInstitutionIds)
            ->where('role', 'doctor')
            ->orderBy('name')
            ->get();





        return response()->json([
            'institutions' => $institutions,
            'doctors' => $doctors,
            'sections' => $sections,
        ]);
    }
    public function datesFilter(Request $request)
    {
        $institutionIds = $request->institutionIds ? $request->institutionIds : null;
        $sectionIds = $request->sectionIds ? $request->sectionIds : null;
        $doctorIds = $request->doctorIds ? $request->doctorIds : null;
        $search = $request->search ? $request->search : "";
        $start = $request->start_date ? $request->start_date : null;
        $end = $request->end_date ? $request->end_date : null;

        $query = Date::latest();
        if ($institutionIds) {

            $selectedInstitutionIdsInSectionsInInstitutionsTable = DB::table('sections_in_institutions')
                ->whereIn('institution_id', $institutionIds)
                ->pluck('id')
                ->toArray();

            $query->whereIn('dates.section_in_institution_id', $selectedInstitutionIdsInSectionsInInstitutionsTable);
        }

        if ($sectionIds) {
            $selectedSectionIdsInSectionsInInstitutionsTable = DB::table('sections_in_institutions')
                ->whereIn('section_id', $sectionIds)
                ->pluck('id')
                ->toArray();

            $query->whereIn('dates.section_in_institution_id', $selectedSectionIdsInSectionsInInstitutionsTable);
        }
        if ($doctorIds) {

            $query->whereIn('doctor_id', $doctorIds);
        }

        if ($start) {
            $query->where('start_time', '>=', $start);
        }

        if ($end) {
            $query->where('end_time', '<=', $end);
        }

        if ($search !== "") {
            $query = $query->whereHas('patient', function ($query) use ($search) {
                $query->where('name', 'LIKE', "%$search%");
            });
        }
        if ($request->reserved == 1) {
            $query->where('reserved', '=', true);
        }

        $dates = $query
            ->with('patient')
            ->with('doctor')
            ->paginate(10);

        return response()->json($dates);
    }
}
