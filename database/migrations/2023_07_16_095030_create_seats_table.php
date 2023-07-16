<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    protected $fillable = ['seat_number', 'is_booked'];

    public static function getAvailableSeats()
    {
        $totalSeats = 80;
        $seatsInRow = 7;
        $lastRowSeats = 3;

        $seats = [];
        $availableSeats = [];

        for ($i = 0; $i < $totalSeats; $i++) {
            $seats[$i] = 0;
        }

        $bookedSeats = self::where('is_booked', true)->pluck('seat_number')->toArray();

        foreach ($bookedSeats as $seatNumber) {
            $seats[$seatNumber - 1] = 1;
        }

        for ($row = 0; $row < ($totalSeats / $seatsInRow); $row++) {
            $rowSeats = array_slice($seats, $row * $seatsInRow, $seatsInRow);
            $availableSeats[] = $rowSeats;
        }

        $lastRowSeats = array_slice($seats, -$lastRowSeats);

        return [
            'seats' => $availableSeats,
            'lastRowSeats' => $lastRowSeats,
        ];
    }

    public function up()
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->integer('seat_number')->unique();
            $table->boolean('is_booked')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
