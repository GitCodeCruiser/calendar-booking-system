<?php

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StartTimeAfterNow implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $date = request()->input(str_replace('start_time', 'date', $attribute));
        
        $datetime = $date . ' ' . $value;
        
        $startTime = Carbon::createFromFormat('Y-m-d H:i:s', $datetime);
        
        if ($startTime->isToday() && $startTime->isPast()) {
            $fail('The start time cannot be in the past.');
        }
    }
}
