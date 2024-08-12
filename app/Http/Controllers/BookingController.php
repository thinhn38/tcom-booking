<?php

namespace App\Http\Controllers;

use App\Http\Actions\Booking\BookingAction;
use App\Http\Actions\Booking\BookingsAction;
use App\Http\Requests\BookingRequest;
use App\Http\Requests\BookingsRequest;
use Illuminate\Http\Response;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function booking(BookingRequest $request, BookingAction $action)
    {
        $result = $action->execute($request->room_id, $request->start_time, $request->end_time);

        if (!$result) {
            return response()->json(['message' => 'Failed'], Response::HTTP_BAD_REQUEST);
        }
        
        return response()->json(['message' => 'Success'], Response::HTTP_CREATED);
    }

    public function bookings(BookingsRequest $request, BookingsAction $action)
    {
        $result = $action->execute($request->bookings);

        if (!$result) {
            return response()->json(['message' => 'Failed'], Response::HTTP_BAD_REQUEST);
        }
        
        return response()->json(['message' => 'Success'], Response::HTTP_CREATED);
    }
}
