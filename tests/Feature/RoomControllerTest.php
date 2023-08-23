<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function testGetRooms()
    {
        Room::factory(3)->create();
        RoomCategory::factory(1)->create();

        $response = $this->get('/api/escape-rooms');

        $response->assertStatus(200)
            ->assertJsonStructure([
                "status",
                "message",
                "data" => [
                    "*" => [
                        "id",
                        "room_no",
                        "status",
                        "user_capacity",
                        "category_id",
                        "category_title",
                        "category_description",
                        "category_amount"
                    ]
                ]
            ]);

        $responseData = $response->json('data');
        $this->assertCount(3, $responseData);

        foreach ($responseData as $roomData) {
            $this->assertArrayHasKey('id', $roomData);
            $this->assertArrayHasKey('room_no', $roomData);
            $this->assertArrayHasKey('status', $roomData);
            $this->assertArrayHasKey('user_capacity', $roomData);
            $this->assertArrayHasKey('category_id', $roomData);
            $this->assertArrayHasKey('category_title', $roomData);
            $this->assertArrayHasKey('category_description', $roomData);
            $this->assertArrayHasKey('category_amount', $roomData);
        }
    }


    public function testGetRoomById()
    {
        $category = RoomCategory::factory()->create();
        $room = Room::factory()->create(['room_category_id' => $category->id]);

        $response = $this->get('/api/escape-rooms/' . $room->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                "status",
                "message",
                "data" => [
                    "id",
                    "room_no",
                    "status",
                    "user_capacity",
                    "category_id",
                    "category_title",
                    "category_description",
                    "category_amount"
                ]
            ]);

        $responseData = $response->json('data');

        $this->assertEquals($room->id, $responseData['id']);
        $this->assertEquals($room->room_no, $responseData['room_no']);
        $this->assertEquals($room->status, $responseData['status']);
        $this->assertEquals($room->user_capacity, $responseData['user_capacity']);
        $this->assertEquals($category->id, $responseData['category_id']);
        $this->assertEquals($category->title, $responseData['category_title']);
        $this->assertEquals($category->desc, $responseData['category_description']);
        $this->assertEquals($category->amount, $responseData['category_amount']);
    }

    public function testGetReservationInfo()
    {
        $room = Room::factory()->create();
        $reservation = Reservation::factory()->create([
            'room_id' => $room->id,
        ]);

        $response = $this->get('/api/escape-rooms/' . $room->id . '/time-slots');

        $response->assertStatus(200)
            ->assertJsonStructure([
                "status",
                "message",
                "data" => [
                    "start_date",
                    "end_date"
                ]
            ]);

        $responseData = $response->json('data');

        $this->assertEquals($reservation->reservation_start->toDateTimeString(), $responseData['start_date']);
        $this->assertEquals($reservation->reservation_end->toDateTimeString(), $responseData['end_date']);
    }



}
