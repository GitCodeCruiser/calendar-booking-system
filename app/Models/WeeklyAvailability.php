<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyAvailability extends Model
{
    use HasFactory;

    protected $fillable = [
        'day_of_week',
        'start_time',
        'end_time',
        'service_id',
        'is_disabled'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function getSlotsAttribute($date)
    {
        $serviceDuration = $this->service->duration;
        $buffer = $this->service->buffer_time;

        $slots = [];

        $checkPastTime = false;
        if($date->format('Y-m-d') == Carbon::today()->format('Y-m-d')) {
            $checkPastTime = true;
            $currentTimeToCheck = strtotime(Carbon::now()->format('H:i:s'));
        }

        $startTime = strtotime($this->start_time);
        $endTime = strtotime($this->end_time);

        $currentTime = $startTime;

        while ($currentTime < $endTime) {
            list($slotStartTime, $slotEndTime) = $this->getSlotTime($currentTime, $serviceDuration, $date);

            // Check if the slot overlaps with any break time (weekly or one-time)
            list($breakTime, $oneTimeBreak) = $this->checkBreakTime($slotStartTime, $slotEndTime, $date);

            if (!$breakTime && !$oneTimeBreak) {
                // Add the slot to the array if it does not overlap with any break time
                $slot = [
                    'start_time' => date('H:i:s', $currentTime),
                    'end_time' => date('H:i:s', $currentTime + strtotime($serviceDuration)),
                ];

                $localStartTime = $currentTime;
                $currentTime += strtotime($serviceDuration);
                $checkTime = strtotime(date('H:i:s', $currentTime));

                // Check if end time is less than the weekly end time
                if($checkTime <= $endTime && (!$checkPastTime || $localStartTime >= $currentTimeToCheck)) {
                    array_push($slots, $slot);
                }
                
                $currentTime += strtotime($buffer);
                $currentTime = strtotime(date('H:i:s', $currentTime));

            } else if ($breakTime) {
                // Skip to the end of the weekly break time if it overlaps with the slot
                $currentTime = strtotime($breakTime->end_time);
            } else if($oneTimeBreak) {
                // Skip to the end of the one-time break time if it overlaps with the slot
                $currentTime = strtotime(Carbon::parse($oneTimeBreak->end_time)->format('H:i:s'));
            }

            
        }

        return $slots;
    }

    // A helper function to get the start and end time of a slot given the current time and service duration
    private function getSlotTime($currentTime, $serviceDuration, $date)
    {
        $slotStartTime = date('H:i:s', $currentTime);
        $slotEndTime = date('H:i:s', $currentTime + strtotime($serviceDuration));

        $slotStartTime = $date->format('Y-m-d') . ' ' . $slotStartTime;
        $slotEndTime = $date->format('Y-m-d') . ' ' . $slotEndTime;

        return array($slotStartTime, $slotEndTime);
    }

    // A helper function to check if a slot overlaps with any break time (weekly or one-time)
    private function checkBreakTime($slotStartTime, $slotEndTime, $date)
    {
        $breakTime = $this->checkWeeklyBreak($slotStartTime, $slotEndTime);
        $oneTimeBreak = $this->checkOneTimeBreak($slotStartTime, $slotEndTime, $date);

        return array($breakTime, $oneTimeBreak);
    }

    // Check if the slot overlaps with any weekly break time (both start time and end time)!
    private function checkWeeklyBreak($slotStartTime, $slotEndTime)
    {
        return $this->service->weeklyBreaks()
         ->where(function ($query) use ($slotStartTime, $slotEndTime) {
             $query->where(function ($subQuery) use ($slotStartTime, $slotEndTime) {
                 $subQuery->where('start_time', '>=', $slotStartTime)
                     ->where('start_time', '<', $slotEndTime);
             })->orWhere(function ($subQuery) use ($slotStartTime, $slotEndTime) {
                 $subQuery->where('end_time', '>', $slotStartTime)
                     ->where('end_time', '<=', $slotEndTime);
             });
         })
         ->first();
    }

    // Check if the slot overlaps with any one-time break time (both start time and end time)
    private function checkOneTimeBreak($slotStartTime, $slotEndTime, $date)
    {
         return $this->service->oneTimeBreaks()
         ->where(function ($query) use ($slotStartTime, $slotEndTime) {
             $query->orWhere(function ($subQuery) use ($slotStartTime, $slotEndTime) {
                 $subQuery->where('start_time', '<', $slotStartTime)
                     ->where('end_time', '>', $slotEndTime);
             })->orWhere(function ($subQuery) use ($slotStartTime, $slotEndTime) {
                 $subQuery->where('start_time', '>', $slotStartTime)
                     ->where('start_time', '<', $slotEndTime);
             })->orWhere(function ($subQuery) use ($slotStartTime, $slotEndTime) {
                 $subQuery->where('end_time', '>', $slotStartTime)
                     ->where('end_time', '<', $slotEndTime);
             });
         })
         ->whereDate('start_time', $date->format('Y-m-d'))
         ->first();
    }
}
