<?php

namespace App\Http\Controllers\Invoices;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Invoices\UserBill;
use App\StudentCard;
use App\CardTransaction;
use Auth;
use DB;

class UserBillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bills=UserBill::where('user_id',Auth::user()->id)->orderBy('paid')->paginate(20);//ne moze paginate na relationship mora ovako
        $card=Auth::user()->card;
        $moneyLeft=CardTransaction::where('student_card_id',$card->id)->sum('money_change');
        return view('invoices.userbills.index')->with(['bills'=>$bills,'moneyLeft'=>$moneyLeft]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bill=UserBill::find($id);
        if($bill==null)
       return redirect()->route('userbills.index')->withErrors(['Bill does not exist.']);
        if($bill->user_id!=Auth::user()->id)
       return redirect()->route('userbills.index')->withErrors(['This bill does not belong to you.']);
        $moneyLeft=CardTransaction::where('student_card_id',Auth::user()->card->id)->sum('money_change');
        if($bill->amount_of_money>$moneyLeft)
       return redirect()->route('userbills.index')->withErrors(['You do not have enough money.']);
        if($bill->paid==true)
        return redirect()->route('userbills.index')->withErrors(['You already paid that bill.']);
        $bill->paid=true;
        DB::transaction(function () use(&$bill) {
            $bill->save();
            $transaction=new CardTransaction;
            $transaction->money_change=-$bill->amount_of_money;
            $transaction->student_card_id=Auth::user()->card->id;
            $transaction->executed_by_user_id=Auth::user()->id;
            $transaction->save();
        },3);
        return redirect()->route('userbills.index')->with('success','Bill paid');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
