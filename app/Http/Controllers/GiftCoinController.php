<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GiftCoin;
use App\Models\User;

class GiftCoinController extends Controller
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
            'from_user_code'    => 'required',
            'to_user_code'      => 'required',
            'coin_type'         => 'required',
            'coins'             => 'required',
        ]);

        $fromUser = $request->from_user_code == 0 ? User::whereType(0)->first() : User::whereCode($request->from_user_code)->first();

        $toUser = User::whereCode($request->to_user_code)->first();

        if ($fromUser == null || $toUser == null) {
            return response()->json([
                'message' => 'unable to found user from code',
            ]);
        }

        $fromCoins = (int) $request->coins;
        $toCoins = (int) $request->coins;

        // update coin balance
        if ($request->coin_type == 1 || $request->coin_type == 5) {
            if ((int) $fromUser->coin_balance < (int) $request->coins) {

                return response()->json([
                    'message' => "{$fromUser->name} has insufficient funds ",
                ]);
            }
            $fromCoins = -$fromCoins;
            $toCoins = $toCoins;
        }

        if ($request->coin_type == 2 || $request->coin_type == 4) {


            if ($request->coin_type == 4 && (int) $toUser->coin_balance < (int) $request->coins) {

                return response()->json([
                    'message' => "{$toUser->name} has insufficient funds to withdraw",
                ]);
            }

            $fromCoins = 0;
            $toCoins = -$toCoins;
        }

        if ($request->coin_type == 3) {
            $fromCoins = 0;
            $toCoins = $toCoins;
        }


        $fromUser->update([
            'coin_balance' => $fromUser->coin_balance + $fromCoins,
        ]);

        $toUser->update([
            'coin_balance' => $toUser->coin_balance + $toCoins,
        ]);

        $giftCoin = GiftCoin::create([
            'from_user_id' => $fromUser->id,
            'to_user_id'   => $toUser->id,
            'coins'        => $request->coins,
            'coin_type'    => $request->coin_type,
        ]);

        return response()->json([
            'coinsData' => $giftCoin,
        ]);
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
