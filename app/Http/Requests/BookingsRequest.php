<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'bookings' => 'array|required',
            'bookings.*.room_id' => ['required', 'exists:rooms,id'],
            'bookings.*.start_time' => ['required', 'date_format:Y-m-d H:i:s', 'after:now'],
            'bookings.*.end_time' => ['required', 'date_format:Y-m-d H:i:s', 'after:start_time']
        ];
    }
}
