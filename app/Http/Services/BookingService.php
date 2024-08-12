<?php

namespace App\Http\Services;

use App\Models\Booking;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class BookingService
{
    public function formatBookingData(
        int $userId,
        int $roomId,
        string $startDatetime,
        string $endDatetime
    ): array {
        $start = Carbon::parse($startDatetime);
        $end = Carbon::parse($endDatetime);

        $bookings = [];

        while ($start->lt($end)) {
            $currentEnd = $start->copy()->endOfDay();

            if ($currentEnd->gt($end)) {
                $currentEnd = $end;
            }

            $bookings[] = [
                'room_id' => $roomId,
                'user_id' => $userId,
                'date_booking' => $start->toDateString(),
                'start_time' => $start->toTimeString(),
                'end_time' => $currentEnd->toTimeString(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

            $start = $start->copy()->addDay()->startOfDay();
        }

        return $bookings;
    }

    public function checkNonOverlappingBookings(array $bookings)
    {
        foreach ($bookings as $index => $booking) {
            $startTime1 = Carbon::parse($booking['start_time']);
            $endTime1 = Carbon::parse($booking['end_time']);
    
            foreach ($bookings as $compareIndex => $compareBooking) {
                if ($index == $compareIndex) {
                    continue;
                }
    
                $startTime2 = Carbon::parse($compareBooking['start_time']);
                $endTime2 = Carbon::parse($compareBooking['end_time']);
    
                if ($startTime1->lt($endTime2) && $endTime1->gt($startTime2)) {
                    return false;
                }
            }
        }
    
        return true;
    }
}