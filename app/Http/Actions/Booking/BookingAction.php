<?php

namespace App\Http\Actions\Booking;

use App\Http\Services\BookingService;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookingAction
{
    public function __construct(
        private BookingService $service,
    ) {
    }

    public function execute(
        int $roomId,
        string $startDatetime,
        string $endDatetime,
    ): bool
    {
        $startDatetime = Carbon::parse($startDatetime);
        $startDate = $startDatetime->format('Y-m-d');
        $startTime = $startDatetime->format('H:i:s');

        $endDatetime = Carbon::parse($endDatetime);
        $endDate = $endDatetime->format('Y-m-d');
        $endTime = $endDatetime->format('H:i:s');
        
        $userId = auth()->user()->id;

        $bookings = Booking::where('user_id', $userId)
            ->where('room_id', $roomId)
            ->whereIn('date_booking', [$startDate, $endDate])
            ->get();
            
        if (count($bookings) > 0) {
            foreach ($bookings as $booking) {
                $dbStartTime = Carbon::parse($booking->start_time);
                $dbEndTime = Carbon::parse($booking->end_time);
                $startTime = Carbon::parse($startTime);
                $endTime = Carbon::parse($endTime);

                if ($booking->date_booking === $startDate || $booking->date_booking === $endDate) {
                    if (
                        ($dbStartTime < $startTime && $dbEndTime > $startTime) ||
                        ($dbStartTime < $endTime && $dbEndTime > $endTime) ||
                        ($dbStartTime <= $startTime && $dbEndTime >= $endTime) ||
                        ($dbStartTime >= $startTime && $dbEndTime <= $endTime)
                    ) {
                        return false;
                    }
                }
            }
        }

        $bookings = $this->service->formatBookingData($userId, $roomId, $startDatetime, $endDatetime);        
        DB::table('bookings')->insert($bookings);

        return true;
    }
}