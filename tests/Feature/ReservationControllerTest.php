<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ReservationControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function testAddReservation()
    {
        $user = User::factory()->create(['user_verified_at' => now()]);
        $room = Room::factory()->create(['status' => 0]);

        $reservationData = [
            'room_id' => $room->id,
            'start_date' => now()->addDay()->toDateTimeString(),
            'end_date' => now()->addDay(2)->toDateTimeString(),
            'user_identity' => [$user->identity_number],
        ];

        $response = $this->actingAs($user)
            ->post('/api/escape-rooms/bookings', $reservationData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                "status",
                "message",
                "data" => [
                    "user_id",
                    "room_number",
                    "room_status",
                    "start_date",
                    "end_date",
                    "total_amount",
                    "birth_day_discount",
                ]
            ]);

        $responseData = $response->json('data');

        $this->assertEquals($user->id, $responseData['user_id']);
        $this->assertEquals($room->room_no, $responseData['room_number']);
        $this->assertEquals(1, $responseData['room_status']); // Assuming room status is set to 1 after reservation
        $this->assertEquals(Carbon::parse($reservationData['start_date']), Carbon::parse($responseData['start_date']));
        $this->assertEquals(Carbon::parse($reservationData['end_date']), Carbon::parse($responseData['end_date']));


        $this->assertDatabaseHas('reservations', [
            'user_id' => $user->id,
            'room_id' => $room->id,
            'reservation_start' => $reservationData['start_date'],
            'reservation_end' => $reservationData['end_date'],
        ]);

        $this->assertDatabaseHas('rooms', [
            'id' => $room->id,
            'status' => 1,
        ]);
    }


    public function testGetReservation()
    {
        $user = User::factory()->create(['user_verified_at' => now()]);
        $reservation1 = Reservation::factory()->create(['user_id' => $user->id]);
        $reservation2 = Reservation::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->get('/api/escape-rooms/bookings/reservation');

        // API response assertion
        $response->assertStatus(200)
            ->assertJsonStructure([
                "status",
                "message",
                "data" => [
                    "*" => [
                        "id",
                        "user_id",
                        "room_number",
                        "start_date",
                        "end_date",
                        "total_amount",
                        "birth_day_discount"
                    ]
                ]
            ]);

        $responseData = $response->json('data');

        foreach ($responseData as $index => $reservationData) {
            $expectedReservation = $index === 0 ? $reservation1 : $reservation2;

            $this->assertEquals($user->id, $reservationData['user_id']);
            $this->assertEquals($expectedReservation->room->room_no, $reservationData['room_number']);
            $this->assertEquals(Carbon::parse($expectedReservation->reservation_start)->format('Y-m-d H:i:s'), $reservationData['start_date']);
            $this->assertEquals(Carbon::parse($expectedReservation->reservation_end)->format('Y-m-d H:i:s'), $reservationData['end_date']);
            $this->assertEquals($expectedReservation->total_amount, $reservationData['total_amount']);
            $this->assertEquals($expectedReservation->birth_day_discount, $reservationData['birth_day_discount']);
        }
    }


    public function testCancelReservation()
    {
        $user = User::factory()->create(['user_verified_at' => now()]);
        $reservation = Reservation::factory()->create(['user_id' => $user->id]);
        $room = $reservation->room;

        $response = $this->actingAs($user)
            ->delete('/api/escape-rooms/bookings/reservation/cancel/' . $reservation->id);

        $response->assertStatus(200)
            ->assertJson([
                "status" => true,
                "message" => "Reservation cancelled successfully",
            ]);

        $this->assertSoftDeleted('reservations', ['id' => $reservation->id]);
        $this->assertDatabaseHas('rooms', ['id' => $room->id, 'status' => 0]);
    }


}
