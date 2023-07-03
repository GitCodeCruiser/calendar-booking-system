<?php

namespace App\Http\Controllers;

use App\Models\WeeklyAvailability;
use Carbon\Carbon;
use App\Http\Requests\WeeklyAvailablityGetRequest;
use App\Models\User;
use App\Http\Requests\BookRequest;
use App\Models\Booking;
use App\Models\Service;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    public function get(WeeklyAvailablityGetRequest $request)
    {
        $date = $request->date;
        $id = $request->service_id;

        $date = Carbon::parse($date);

        $dayOfWeek = $date->dayOfWeek;

        $weeklyAvailability = WeeklyAvailability::when($id, function ($query, $id) {
                $query->where('service_id', $id);
            })
            ->where('day_of_week', $dayOfWeek)
            ->with('service.weeklyBreaks')
            ->first();

        if (!$weeklyAvailability) {
            return  sendSuccess('Success', []);
        }

        $weeklyAvailability->available_slots = $weeklyAvailability->getSlotsAttribute($date);

        return sendSuccess('Success', $weeklyAvailability);
    }

    public function store(BookRequest $request)
    {
        $bookings = [];

        DB::beginTransaction();

        foreach ($request->all() as $key => $value) {
            $serviceId = WeeklyAvailability::find($value['weekly_availability_id'])->service_id;

            $weeklyAvailabilityGetRequest = new WeeklyAvailablityGetRequest();

            $weeklyAvailabilityGetRequest->merge([
                'date' => $value['date'],
                'service_id' => $serviceId
            ]);

            $weeklyAvailability = $this->get($weeklyAvailabilityGetRequest);

            if($weeklyAvailability['data']) {
                // Check if the slot exists in the available slots array
                $isSlotExists = in_array(
                    [
                        'start_time' => $value['start_time'],
                        'end_time' => $value['end_time']
                    ],
                    $weeklyAvailability['data']['available_slots'],
                    true
                );

                if ($isSlotExists && ($value['weekly_availability_id'] == $weeklyAvailability['data']['id'])) {
                    $alreadyBookedCount = Booking::where('start_time', $value['date'] . ' ' . $value['start_time'])
                        ->where('end_time', $value['date'] . ' ' . $value['end_time'])
                        ->where('weekly_availability_id', $value['weekly_availability_id'])
                        ->count();

                    $allowedCount = Service::where('id', $serviceId)->first()->max_appointments_per_slot;

                    // If the already booked count is equal to the allowed count, return an error message
                    if ($alreadyBookedCount == $allowedCount) {
                        return sendError("Slots cannot be booked due to max count allowed.");
                    }

                    $record = User::firstOrNew([
                        'first_name' => $value['first_name'],
                        'last_name' => $value['last_name'],
                        'email' => $value['email']
                    ]);

                    $record->save();

                    $userId = User::where('email', $value['email'])->first()->id;

                    // Prepare the booking data array
                    $data = [];
                    $data['start_time'] = $value['date'] . ' ' . $value['start_time'];
                    $data['end_time'] = $value['date'] . ' ' . $value['end_time'];
                    $data['weekly_availability_id'] = $value['weekly_availability_id'];
                    $data['user_id'] = $userId;
 
                    $booking = Booking::create($data);

                    array_push($bookings, $booking);
                } else {
                    return sendError("At least one slot is not valid.");
                }
            } else {
                return sendError("No Slot Available.");
            }

        }

        DB::commit();

        return sendSuccess('Bookings Added Successfully!', $bookings);
    }
}
