<?php

namespace App\Rules;

use App\Models\Service;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Carbon\Carbon;

class SchedulingWindow implements ValidationRule
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $schedulingWindow = Service::where('id', $this->id)->pluck('scheduling_window')->first();
        $endDate = Carbon::now()->addDays($schedulingWindow+1)->toDateString();

        if($value >= $endDate) {
            $fail('The :attribute field must be a date less then or equal to ' . $schedulingWindow . ' days from now.');
        }
    }
}
