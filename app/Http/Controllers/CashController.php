<?php

namespace App\Http\Controllers;

use App\Http\Resources\CashResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Str;

class CashController extends Controller
{
    public function index()
    {
         $debit = Auth::user()->cash()->where('amount','>=',0)
                                      ->whereBetween('when',[now()->firstOfMonth(),now()])
                                      ->get('amount')
                                      ->sum('amount');
         $credit = Auth::user()->cash()->where('amount','<',0)
                                     ->whereBetween('when',[now()->firstOfMonth(),now()])
                                     ->get('amount')
                                     ->sum('amount');
        
        $balances = Auth::user()->cash()->get('amount')->sum('amount');
        $transaction =  Auth::user()->cash()->whereBetween('when',[now()->firstOfMonth(),now()])
                                            ->latest()->get();

         return response()->json([
             'message' => 'Data Success',
             'debit' => $debit,
             'kredit' => $credit,
             'balance' => $balances,
             'transaction' => CashResource::collection($transaction)
         ]);
    }

    public function store()
    {
        request()->validate([
            'name' => 'required',
            'amount' => 'required|numeric'
        ]);

        
        $slug = Str::slug(request('name') . "-" . Str::random(6));
        $when = request('when') ?? now();

        Auth::user()->cash()->create([
            'name' => request('name'),
            'slug' => $slug,
            'description' => request('description'),
            'when' => $when,
            'amount' => request('amount')
        ]);

        return response()->json([
            'message' => 'the transaction was successfull'
        ]);


    }
}
