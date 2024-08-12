<?php

namespace App\Http\Actions\Booking;

use App\Http\Services\BookingService;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class BookingsAction
{
    public function __construct(
        private BookingService $service,
    ) {
    }

    public function execute(array $bookings): bool
    {
        $userId = auth()->user()->id;
        $cloneBookings = $bookings;
        $dbBookings = Booking::where('user_id', $userId)->where('date_booking', ">=", date('Y-m-d'))->get();
        dd($dbBookings);

        foreach ($dbBookings as $dbBooking) {
            $bookings[] = [
                'room_id' => $dbBooking->room_id,
                'start_time' => "$dbBooking->date_booking $dbBooking->start_time",
                'end_time' => "$dbBooking->date_booking $dbBooking->end_time",
            ];
        }

        $bookings = collect($bookings)->groupBy('room_id')->toArray();

        foreach ($bookings as $roomBookings) {
            if (!$this->service->checkNonOverlappingBookings($roomBookings)) {
                return false;
            }
        }

        $insertBookings = [];
        foreach ($cloneBookings as $booking) {
            $insertBookings = array_merge($this->service->formatBookingData(
                $userId,
                $booking['room_id'],
                $booking['start_time'],
                $booking['end_time']
            ), $insertBookings);
        }
        
        DB::table('bookings')->insert($insertBookings);

        return true;
    }
}