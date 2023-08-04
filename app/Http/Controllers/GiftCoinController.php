<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GiftCoin;

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
            'user_id' => 'required',
            'available_coins' => 'required',
            'purchase_coins' => 'required',
            'lose_coins' => 'required',
            'won_coins' => 'required',
            'withdraw_coins' => 'required',
           

           
        ]);
    //   echo 'hell';
      return $giftCoin = GiftCoin::create([
        'user_id' => $request->user_id,
        'available_coins' => $request->available_coins,
        'purchase_coins' => $request->purchase_coins,
        'lose_coins' => $request->lose_coins,
        'won_coins' => $request->won_coins,
        'withdraw_coins' => $request->withdraw_coins,
       
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
