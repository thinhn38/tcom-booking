<?php

namespace Tests\Feature;

use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_a_booking_same_day()
    {
        $user = User::factory()->create();
        $room = Room::factory()->create();

        $now = Carbon::now();
        $nextDay = $now->addDays(1);
        $startOfDay = $nextDay->startOfDay(); // 00:00:00
        $startTime = $startOfDay->addHours(1); // 01:00:00
        $endTime = $startOfDay->copy()->addHours(2); // 02:00:00

        $response = $this->actingAs($user)
                        ->postJson('/api/booking', [
                            'room_id' => $room->id,
                            'start_time' => $startTime->format('Y-m-d H:i:s'),
                            'end_time' => $endTime->format('Y-m-d H:i:s'),
                        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'room_id' => $room->id,
            'date_booking' => $startOfDay->format('Y-m-d'),
            'start_time' => $startTime->format('H:i:s'),
            'end_time' => $endTime->format('H:i:s'),
        ]);
    }

    public function test_can_create_a_booking_other_day()
    {
        $user = User::factory()->create();
        $room = Room::factory()->create();

        $now = Carbon::now();
        $nextDay = $now->addDays(1);
        $startOfDay = $nextDay->startOfDay(); // 00:00:00
        $startTime = $startOfDay->addHours(1); // 01:00:00
        $endTime = $startOfDay->copy()->addDays(1)->addHours(2); // 02:00:00

        $response = $this->actingAs($user)
                        ->postJson('/api/booking', [
                            'room_id' => $room->id,
                            'start_time' => $startTime->format('Y-m-d H:i:s'),
                            'end_time' => $endTime->format('Y-m-d H:i:s'),
                        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'room_id' => $room->id,
            'date_booking' => $startTime->format('Y-m-d'),
            'start_time' => $startTime->format('H:i:s'),
            'end_time' => $startTime->copy()->endOfDay()->format('H:i:s'),
        ]);
        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'room_id' => $room->id,
            'date_booking' => $endTime->format('Y-m-d'),
            'start_time' => $endTime->copy()->startOfDay()->format('H:i:s'),
            'end_time' => $endTime->format('H:i:s'),
        ]);
    }

    public function test_cannot_create_a_booking_valid_request()
    {
        $user = User::factory()->create();
        $room = Room::factory()->create();

        $response = $this->actingAs($user)
                        ->postJson('/api/booking', [
                            'room_id' => $room->id,
                            'start_time' => '2024-07-01 10:00:00',
                            'end_time' => '2024-07-01 05:00:00',
                        ]);
        $response->assertStatus(422);
    }

    public function test_cannot_create_a_booking_overlap_booking()
    {
        $user = User::factory()->create();
        $room = Room::factory()->create();

        $now = Carbon::now();
        $nextDay = $now->addDays(1);
        $startOfDay = $nextDay->startOfDay(); // 00:00:00
        $startTime = $startOfDay->addHours(1); // 01:00:00
        $endTime = $startOfDay->copy()->addHours(15); // 16:00:00

        $response = $this->actingAs($user)
                        ->postJson('/api/booking', [
                            'room_id' => $room->id,
                            'start_time' => $startTime->format('Y-m-d H:i:s'),
                            'end_time' => $endTime->format('Y-m-d H:i:s'),
                        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'room_id' => $room->id,
            'date_booking' => $startOfDay->format('Y-m-d'),
            'start_time' => $startTime->format('H:i:s'),
            'end_time' => $endTime->format('H:i:s'),
        ]);

        $response = $this->actingAs($user)
                        ->postJson('/api/booking', [
                            'room_id' => $room->id,
                            'start_time' => $startOfDay->copy()->addHours(3)->format('Y-m-d H:i:s'),
                            'end_time' => $startOfDay->copy()->addHours(10)->format('Y-m-d H:i:s'),
                        ]);
        
        $response->assertStatus(400);
    }
}