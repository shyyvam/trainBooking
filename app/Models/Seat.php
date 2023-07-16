<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    protected $table = 'seats';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public static function getAvailableSeats()
    {
        $totalSeats = 80;
        $seatsInRow = 7;
        $lastRowSeats = 3;

        $availableSeats = [];
        $lastRowSeatsData = [];

        $pageSize = 1000; // Adjust the page size as needed
        $totalPages = ceil($totalSeats / $pageSize);

        for ($page = 1; $page <= $totalPages; $page++) {
            $seatsData = self::where('id', '>=', ($page - 1) * $pageSize + 1)
                ->where('id', '<=', $page * $pageSize)
                ->orderBy('id')
                ->get();

            $seats = [];
            foreach ($seatsData as $seat) {
                $seats[$seat->id] = $seat->is_booked;
            }

            for ($row = 0; $row < $pageSize / $seatsInRow; $row++) {
                $rowSeats = [];
                for ($i = 0; $i < $seatsInRow; $i++) {
                    $seatId = ($page - 1) * $pageSize + $row * $seatsInRow + $i + 1;
                    if ($seatId <= $totalSeats) {
                        $rowSeats[$seatId] = $seats[$seatId] ?? false;
                    }
                }
                $availableSeats[] = $rowSeats;
            }

            if ($page == $totalPages) {
                $lastRowStart = ($totalSeats / $seatsInRow - 1) * $seatsInRow + 1;
                for ($i = 0; $i < $lastRowSeats; $i++) {
                    $seatId = $lastRowStart + $i;
                    if ($seatId <= $totalSeats) {
                        $lastRowSeatsData[$seatId] = $seats[$seatId] ?? false;
                    }
                }
            }
        }

        return [
            'seats' => $availableSeats,
            'lastRowSeats' => $lastRowSeatsData,
        ];
    }
}
