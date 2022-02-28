<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Card;
use App\User;
use App\StudentCard;
use App\CardTransaction;
use Carbon\Carbon;
use DB;
use Auth;

class StudentCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        
        $this->middleware(['CheckRole:admin']);//samo admin moze da pristupe ovom delu 
    }
    public function index()
    {
        $studentcards=StudentCard::
        join('users as cardholder','cardholder.id','=','student_cards.cardholder_id')->
        join('cards','cards.id','=','student_cards.card_type_id')->
        join('users as issuer','issuer.id','=','student_cards.issuer_id')->
        leftJoin('card_transactions','card_transactions.student_card_id','=','student_cards.id')->
        select('student_cards.*','cards.name as card_type','cards.validity_days','student_cards.renewed_at as renewed',DB::raw('DATE_ADD(student_cards.renewed_at,INTERVAL cards.validity_days DAY) as expires_at'),'cardholder.name as cardholder_name','cardholder.surname as cardholder_surname','issuer.name as issuer_name','issuer.surname as issuer_surname',DB::raw('COALESCE(sum(card_transactions.breakfasts_change),0) as breakfastsLeft'),DB::raw('COALESCE(sum(card_transactions.lunches_change),0) as lunchesLeft'),DB::raw('COALESCE(sum(card_transactions.dinners_change),0) as dinnersLeft'),DB::raw('COALESCE(sum(card_transactions.money_change),0) as moneyLeft'))
        ->groupBy('student_cards.id')->get();
        //...as renewed baguje ako ostane renwed_at posto ga laravel "mutira/pretvori" u datetime zato sto je setovano u modelu u $dates
        return view('studentcards.index')->with('studentcards',$studentcards);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cards=Card::all();
        return view('studentcards.create')->with('cards',$cards);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function renew(Request $request)
    {
        $this->validate($request,[
            'student_card'=>['integer','exists:student_cards,id'],
        ]);
        $cards=Card::all();
        $studentcard=StudentCard::where('student_cards.id',$request->input('student_card'))->join('users as cardholder','cardholder.id','=','student_cards.cardholder_id')->select('student_cards.id','student_cards.card_type_id','cardholder.name as cardholder_name','cardholder.surname as cardholder_surname','renewed_at')->firstOrFail();
        //dd($studentcard);
        return view('studentcards.edit')->with(['cards'=>$cards,'studentcard'=>$studentcard,'renewing'=>1]);
    }
    public function store(Request $request)
    {
         //DB::enableQueryLog();
         $user_id;
        $this->validate($request,[
        'card'=>['integer','exists:cards,id'],
        'email'=>['email',function($attribute,$value,$fail) use($request,&$user_id)
        {
          
            $user_id=User::where('email',$request->input('email'))->pluck('id')->first();
            if($user_id==null)//znaci da nije nadjen
            $fail('The selected email is invalid.');
            $studentcards=StudentCard::where('cardholder_id',$user_id)->pluck('id')->first();
            if($studentcards!=null)
            $fail('Student already has student card.');
            //dd(DB::getQueryLog());
        }]
        ]);
        $studentcard=new StudentCard;
        $studentcard->card_type_id=$request->input('card');
        $studentcard->cardholder_id=$user_id;
        $studentcard->issuer_id=Auth::user()->id;
        $studentcard->renewed_at=date('Y-m-d');
        $studentcard->save();
        return redirect()->route('dashboard')->with('success','New student card issued');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $studentcard=StudentCard::where('student_cards.id',$id)->
        join('users as cardholder','cardholder.id','=','student_cards.cardholder_id')->
        join('cards','cards.id','=','student_cards.card_type_id')->
        join('users as issuer','issuer.id','=','student_cards.issuer_id')->
        leftJoin('card_transactions','card_transactions.student_card_id','=','student_cards.id')->
        select('student_cards.*','cards.name as card_type','cards.validity_days','student_cards.renewed_at',DB::raw('DATE_ADD(student_cards.renewed_at,INTERVAL cards.validity_days DAY) as expires_at'),'cardholder.name as cardholder_name','cardholder.surname as cardholder_surname','issuer.name as issuer_name','issuer.surname as issuer_surname',DB::raw('COALESCE(sum(card_transactions.breakfasts_change),0) as breakfastsLeft'),DB::raw('COALESCE(sum(card_transactions.lunches_change),0) as lunchesLeft'),DB::raw('COALESCE(sum(card_transactions.dinners_change),0) as dinnersLeft'),DB::raw('COALESCE(sum(card_transactions.money_change),0) as moneyLeft'))
        ->first();
        //DB::enableQueryLog();
        $transactions=DB::table('card_transactions as transactions')->join('users','users.id','transactions.executed_by_user_id')->where('student_card_id',$studentcard->id)->select('transactions.breakfasts_change','transactions.lunches_change','transactions.dinners_change','transactions.money_change','transactions.created_at','users.name as executed_name','users.surname as executed_surname')->orderByDesc('created_at')->get();
      //  dd(DB::getQueryLog());
     // return $studentcard;
        return view('studentcards.show')->with(['studentcard'=>$studentcard,'transactions'=>$transactions]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cards=Card::all();
        $studentcard=StudentCard::where('student_cards.id',$id)->join('users as cardholder','cardholder.id','=','student_cards.cardholder_id')->select('student_cards.id','student_cards.card_type_id','cardholder.name as cardholder_name','cardholder.surname as cardholder_surname','renewed_at')->firstOrFail();
        //dd($studentcard);
        return view('studentcards.edit')->with(['cards'=>$cards,'studentcard'=>$studentcard]);
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
        $this->validate($request,[
            'card'=>['integer','exists:cards,id'],
            ]);
        $studentcard=StudentCard::findOrFail($id);
        $studentcard->card_type_id=$request->input('card');
        if($request->has('renew'))
        {
            $studentcard->renewed_at=Carbon::now();//moze i ovaj format postoj date mutator
        }
        $studentcard->save();////!!!!!
        if($request->has('renew'))
        {
            if($request->input('renew')==1)//kada radimo renew kartice sa dashboarda renew checkbox je ugasen,samim tim ga forma ne submituje,zato imamo hidden parametar renew koji ima vrednost 1
             return redirect()->route('dashboard')->with('success','Student card renewed');
            return redirect()->route('studentcards.index')->with('success','Student card renewed');
        }
        return redirect()->route('studentcards.index')->with('success','Student card edited');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        StudentCard::find($id)->delete();
        return redirect()->route('studentcards.index')->with('success','Student card deleted');
    }
}
