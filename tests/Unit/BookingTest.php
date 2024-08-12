<?php

namespace Tests\Unit;

use App\Http\Services\BookingService;
use Mockery;
use PHPUnit\Framework\TestCase;

class BookingTest extends TestCase
{
    public function test_no_overlapping_bookings()
    {
        $bookings = [
            [
                'start_time' => '2024-07-01 10:00:00',
                'end_time' => '2024-07-01 17:00:00',
            ],
            [
                'start_time' => '2024-07-01 18:00:00',
                'end_time' => '2024-07-01 19:00:00',
            ]
        ];
        $service = new BookingService();
        $result = $service->checkNonOverlappingBookings($bookings);
        $this->assertTrue($result);
    }

    public function test_overlapping_bookings()
    {
        $bookings = [
            [
                'start_time' => '2024-07-01 10:00:00',
                'end_time' => '2024-07-01 17:00:00',
            ],
            [
                'start_time' => '2024-07-01 16:00:00',
                'end_time' => '2024-07-01 19:00:00',
            ]
        ];
        $service = new BookingService();
        $result = $service->checkNonOverlappingBookings($bookings);
        $this->assertFalse($result);
    }
}
