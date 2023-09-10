<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Races;
use Carbon\Carbon;

class RacesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'start_time' => 'required|date_format:h:i A',
            'winner_horse_no' => 'required',
        ]);

        $proposedStartTime = \Carbon\Carbon::createFromFormat('h:i A', $request->start_time);

        $existingSlots = Races::whereDate('created_at', '=', now()->format('Y-m-d'))->count();

        if ($existingSlots > 0) {

            $existingSlot = Races::whereDate('created_at', '=', now()->format('Y-m-d'))
                ->where('start_time', $proposedStartTime->format('h:i A'))
                ->exists();

            if ($existingSlot) {
                return response()->json([
                    'error' => 'Cannot book slot. Another slot is already booked at the proposed start time.'
                ], 422);
            }

            $existingSlotTimes = Races::whereDate('created_at', '=', now()->format('Y-m-d'))
                ->pluck('start_time')->map(function ($time) {
                    return \Carbon\Carbon::createFromFormat('h:i A', $time);
                });

            $validSlot = $existingSlotTimes->contains(function ($time) use ($proposedStartTime) {
                return $time->diffInMinutes($proposedStartTime) === 60;
            });

            if (!$validSlot) {
                return response()->json([
                    'error' => 'Cannot book slot. The slot should be exactly 1 hour before or 1 hour after an existing slot.'
                ], 422);
            }
        }

        $bids = Races::create([
            'start_time'      => $proposedStartTime->format('h:i A'),
            'winner_horse_no' => $request->winner_horse_no,
        ]);

        return response()->json([
            'bids' => $bids
        ], 201);
    }


    public function checkRaceAvailability(Request $request)
    {
        $request->validate([
            'datetime' => 'required|string',
        ]);

        $requestedDatetime = Carbon::parse($request->datetime);

        $requestedTime = $requestedDatetime->format('h:i A');

        $existingRace = Races::whereDate('created_at', '=', $requestedDatetime->format('Y-m-d'))
            ->where(function ($query) use ($requestedTime) {
                $query->whereBetween('start_time', [
                    Carbon::parse($requestedTime)->subMinutes(5)->format('h:i A'),
                    Carbon::parse($requestedTime)->addMinutes(5)->format('h:i A'),
                ])->orWhere('start_time', $requestedTime);
            })->first();

        // dd()
        if ($existingRace) {
            return response()->json([
                'message' => 'Race is available on the specified time.',
                'winner_horse_no' => $existingRace->winner_horse_no,
            ]);
        }

        return response()->json([
            'message' => 'No race available on the specified time within the next 5 minutes.',
            'winner_horse_no' => null,
        ]);
    }

    public function updateWinnerHorse(Request $request)
    {
        $race = Races::whereStartTime($request->start_time)->first();

        if (!$race) {
            return response()->json(['error' => 'Race not found'], 404);
        }

        $race->update(['winner_horse_no' => $request->winner_horse_no]);

        return response()->json(['message' => 'Winner horse updated successfully', 'race' => $race]);
    }




    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
