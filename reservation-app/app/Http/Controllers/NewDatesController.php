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
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class NewDatesController extends Controller
{
    public function insertDates(Request $request)
    {
        $sectionIds = $request->sectionIds;
        $doctorIds = $request->doctorIds;
        $institutionIds = $request->institutionIds;
        $startTime = new DateTime($request->startTime);
        $endTime = new DateTime($request->endTime);
        $step = $request->step;
        $isInserted = 0;
        DB::beginTransaction();

        try {
            foreach ($institutionIds as $institutionId) {
                foreach ($sectionIds as $sectionId) {
                    $sectionInInstitution = DB::table('sections_in_institutions')
                        ->where('institution_id', $institutionId)
                        ->where('section_id', $sectionId)
                        ->first();

                    if ($sectionInInstitution) {
                        foreach ($doctorIds as $doctorId) {
                            $doctor = User::where('id', $doctorId)
                                ->where('section_in_institution_id', $sectionInInstitution->id)
                                ->first();
                            if ($doctor) {
                                $currentTime = clone $startTime;
                                $currentTime = clone $startTime;

                                
                                while ($currentTime->format('Y-m-d') <= $endTime->format('Y-m-d')) {
                                    
                                    $currentStartTime = (clone $currentTime)->setTime((int)$startTime->format('H'), (int)$startTime->format('i'), 0);
                                    $currentEndTime = (clone $currentTime)->setTime((int)$endTime->format('H'), (int)$endTime->format('i'), 0);

                                    if ($currentEndTime > $endTime) {
                                        $currentEndTime = (clone $endTime)->setTime((int)$endTime->format('H'), (int)$endTime->format('i'), 0);
                                    }

                                    if ($currentStartTime < $startTime) {
                                        $currentStartTime = (clone $startTime)->setTime((int)$startTime->format('H'), (int)$startTime->format('i'), 0);
                                    }

                                    $currentPeriodTime = clone $currentStartTime;
                                    while ($currentPeriodTime < $currentEndTime) {
                                        $newEndTime = (clone $currentPeriodTime)->add(new DateInterval('PT' . $step . 'M'));
                                        if ($newEndTime > $currentEndTime) {
                                            break;
                                        }

                                        $existingRecord = Date::where('section_in_institution_id', $sectionInInstitution->id)
                                            ->where(function ($query) use ($currentPeriodTime, $newEndTime) {
                                                $query->where(function ($subQuery) use ($currentPeriodTime) {
                                                    $subQuery->where('start_time', '<=', $currentPeriodTime)
                                                        ->where('end_time', '>', $currentPeriodTime);
                                                })->orWhere(function ($subQuery) use ($newEndTime) {
                                                    $subQuery->where('start_time', '<', $newEndTime)
                                                        ->where('end_time', '>=', $newEndTime);
                                                })->orWhere(function ($subQuery) use ($currentPeriodTime, $newEndTime) {
                                                    $subQuery->where('start_time', '>=', $currentPeriodTime)
                                                        ->where('end_time', '<=', $newEndTime);
                                                });
                                            })->where('doctor_id', $doctorId)
                                            ->exists();

                                        if (!$existingRecord) {
                                            $date = new Date;
                                            $date->doctor_id = $doctorId;
                                            $date->start_time = clone $currentPeriodTime;
                                            $date->end_time = clone $newEndTime;
                                            $date->section_in_institution_id = $sectionInInstitution->id;
                                            $date->save();
                                            $isInserted += 1;
                                        }

                                        $currentPeriodTime->add(new DateInterval('PT' . $step . 'M'));
                                    }

                                    
                                    $currentTime->add(new DateInterval('P1D'));
                                }
                            }
                        }
                    }
                }
            }

            DB::commit();

            if ($isInserted > 0) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['info' => 'The parameters are wrong ']);
            }
        } catch (\Exception $e) {
            DB::rollback(); 
            return response()->json(['error' => 'Error while inserting dates.'], 500);
        }
    }
    public function reservation(Request $request)
    {

        $appointmentId = $request->id;
        $reason = $request->reason ? $request->reason : null;
        // find appointbent by  appointment_id 
        $appointment = Date::find($appointmentId);
        





        $alreadyReservedToday = Date::whereRaw("DATE(start_time) = DATE('" . $appointment->start_time . "')")
            ->where('patient_id', Auth::user()->id)
            ->where('reserved', true)
            ->exists();

        if ($alreadyReservedToday) {
            return response()->json(['error' => 'You have already booked an appointment for this day'], 422);
        }

        // check the appointment
        if ($appointment && !$appointment->reserved) {
            // set the appointment
            $appointment->reserved = true;
            $appointment->patient_id = Auth::user()->id;
            $appointment->reason = $reason;
            $appointment->cnp = Auth::user()->identity_number;
            $appointment->birthdate = Auth::user()->birthdate;
            $appointment->phone = Auth::user()->phone;
            // save the appointment
            $appointment->save();

            // send response
            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => 'The time is no longer available, please choose another time']);
        }
        
    }

    public function reservationWithoutLogin(Request $request)
    {

        $appointmentId = $request->id;
        $reason = $request->reason ? $request->reason : null;
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,

            ]);
        }
        
        $appointment = Date::find($appointmentId);
        
        $alreadyReservedToday = Date::whereRaw("DATE(start_time) = DATE('" . $appointment->start_time . "')")
            ->where('patient_id', $user->id)
            ->where('reserved', true)
            ->exists();

        if ($alreadyReservedToday) {
            return response()->json(['error' => 'You have already booked an appointment for this day']);
        }
        if ($appointment->reserved) {
            return response()->json(['error' => 'The time is no longer available, please choose another time']);
        }
        if ($appointment && !$appointment->reserved) {
            $appointment->reserved = true;
            $appointment->patient_id = $user->id;
            $appointment->reason = $reason;
            $appointment->cnp = $user->identity_number ? $user->identity_number : null;
            $appointment->birthdate = $user->birthdate;
            $appointment->phone = $user->phone;
            $appointment->save();

            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => 'An error occurred, please try again']);
        }


    }
}
