<?php

namespace App\Http\Controllers;

use App\Models\Seat;
use Illuminate\Http\Request;




class SeatController extends Controller
{

    public function index()
    {
        return view('seats.index');
    }

    public  function checkConsecutiveSeats($row, $numSeats, $seats)
    {
        $seatsInRow = 7;

        for ($i = $row * $seatsInRow; $i < ($row + 1) * $seatsInRow - $numSeats + 1; $i++) {
            $consecutive = true;

            for ($j = 0; $j < $numSeats; $j++) {
                if ($seats[$i + $j] != 0) {
                    $consecutive = false;
                    break;
                }
            }

            if ($consecutive) {
                return $i;
            }
        }

        return -1;
    }


    /**
     * @param $numSeats
     * @param $seats
     * @return array
     */
    public function book(Request $request)
    {

        $numSeats = $request->numseats;
        $totalSeats = Seat::all()->count();
        $seatsInRow = 7;
        $lastRowSeats = 3;

        $seats = array_fill(0, $totalSeats, 0);
        dd($seats);

        if ($numSeats > $totalSeats) {
            echo "Sorry, the number of seats requested exceeds the total number of seats available.";
            return [];
        }

        if ($numSeats > $seatsInRow) {
            echo "Sorry, the maximum number of seats that can be booked at a time is 7.";
            return [];
        }

        // Check for consecutive seats in one row
        for ($row = 0; $row < $totalSeats / $seatsInRow; $row++) {
            $seatIndex = self::checkConsecutiveSeats($row, $numSeats,$seats);
            if ($seatIndex != -1) {
                for ($i = $seatIndex; $i < $seatIndex + $numSeats; $i++) {
                    $seats[$i] = 1;
                }
                return range($seatIndex + 1, $seatIndex + $numSeats + 1);
            }
        }

        // If no consecutive seats found, book nearby seats in different rows
        for ($i = 0; $i < $totalSeats; $i++) {
            if ($seats[$i] == 0) {
                $numBookedSeats = 1;
                while ($numBookedSeats < $numSeats && $i + $numBookedSeats < $totalSeats) {
                    if ($seats[$i + $numBookedSeats] == 1) {
                        break;
                    }
                    $numBookedSeats++;
                }
                if ($numBookedSeats == $numSeats) {
                    for ($j = $i; $j < $i + $numBookedSeats; $j++) {
                        $seats[$j] = 1;
                    }
                    return range($i + 1, $i + $numBookedSeats + 1);
                }
            }
        }

        // If nearby seats are not available, book seats in different rows
        $numBookedSeats = 0;
        $bookedSeats = [];
        for ($i = 0; $i < $totalSeats; $i++) {
            if ($seats[$i] == 0) {
                $seats[$i] = 1;
                $numBookedSeats++;
                $bookedSeats[] = $i + 1;
                if ($numBookedSeats == $numSeats) {
                    return $bookedSeats;
                }
            }
        }
        return [];
    }

}
