<?php

namespace App\Http\Requests;

use App\Rules\StartTimeAfterNow;
use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            '*.first_name' => 'required|string',
            '*.last_name' => 'required|string',
            '*.email' => 'required|email',
            '*.start_time' => ['required', 'date_format:H:i:s', new StartTimeAfterNow()],
            '*.end_time' => 'required|date_format:H:i:s',
            '*.date' => 'required|after_or_equal:today|date_format:Y-m-d',
            '*.weekly_availability_id' => 'required|exists:weekly_availabilities,id'
        ];
    }
}
