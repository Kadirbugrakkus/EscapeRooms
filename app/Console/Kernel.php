<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */

    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $now = now();

            // Rezervasyonların süresi dolmuş olanları bul
            $expiredReservations = Reservation::where('reservation_end', '<', $now)->get();

            foreach ($expiredReservations as $reservation) {
                // Odayı tekrar 0 yap
                $room = Room::find($reservation->room_id);
                if ($room) {
                    $room->update(['status' => 0]);
                }

                // Rezervasyonu sil
                $reservation->delete();

                // Gerekirse loglama yapabilirsiniz
                Log::info("Reservation expired and removed: Reservation ID {$reservation->id}");
            }
        })->everyFiveSeconds();
    }


    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
