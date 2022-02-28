<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\StudentCard;
use App\CardTransaction;
use App\Card;
use Carbon\Carbon;
use DB;

class ApiStudentCardController extends Controller
{
    public function cardInfo($card_id)
    {
        $card_info=StudentCard::join('users','student_cards.cardholder_id','users.id')->join('cards','student_cards.card_type_id','cards.id')->where('student_cards.id',$card_id)->select('student_cards.id as card_id','users.name as cardholder_name','users.surname as cardholder_surname','cards.breakfast_price','cards.lunch_price','cards.dinner_price')->first();
        $mealsleft=CardTransaction::where('student_card_id',$card_id)->select(DB::raw('COALESCE(SUM(breakfasts_change),0) as breakfasts_on_card,COALESCE(SUM(lunches_change),0) as lunches_on_card,COALESCE(SUM(dinners_change),0) as dinners_on_card,COALESCE(SUM(money_change),0) as money_on_card'))->first();
        $mealsallowed=$this->allowedNumberOfMeals($card_id);
        $card_info->setAttribute('breakfasts_on_card',$mealsleft["breakfasts_on_card"]);
        $card_info->setAttribute('lunches_on_card',$mealsleft["lunches_on_card"]);
        $card_info->setAttribute('dinners_on_card',$mealsleft["dinners_on_card"]);
        $card_info->setAttribute('money_on_card',$mealsleft["money_on_card"]);

        $card_info->setAttribute('breakfasts_allowed',$mealsallowed["breakfasts"]);
        $card_info->setAttribute('lunches_allowed',$mealsallowed["lunches"]);
        $card_info->setAttribute('dinners_allowed',$mealsallowed["dinners"]);
        return $card_info;
    }
    public function getCardInfo(Request $request)
    {
        $this->validate($request,[
            'id'=>['required','integer','exists:student_cards,id'],]);
 
       // dd($request);
        //return response(['message'=>'Invalid login credentials'],401);
        $this->validate($request,[
            'id'=>[function ($attribute, $value, $fail) use($request) {
                $card=StudentCard::where('student_cards.id',$request->input('id'))->join('cards','cards.id','student_cards.card_type_id')->select('student_cards.renewed_at','cards.validity_days')->first();
                $expires_at=Carbon::parse($card->renewed_at)->addDays($card->validity_days);
                $expired=$expires_at->lessThanOrEqualTo(Carbon::now());
                
                    if($expired)
                    {
                    $fail('Card expired.');
                    }
                }],
        ]);
        return $this->cardInfo($request->input('id'));
    }
  
    public function createCafeteriaCardTransaction(Request $request)
    {
        $request->validate([
            'card_id'=>['required','integer','exists:student_cards,id',function ($attribute, $value, $fail) use($request) {
                $card=StudentCard::where('student_cards.id',$request->input('card_id'))->join('cards','cards.id','student_cards.card_type_id')->select('student_cards.renewed_at','cards.validity_days')->first();
                $expires_at=Carbon::parse($card->renewed_at)->addDays($card->validity_days);
                $expired=$expires_at->lessThanOrEqualTo(Carbon::now());
                
                    if($expired)
                    {
                    $fail('Card expired.');
                    }
                }],
            'breakfasts_change'=>'required|integer',
            'lunches_change'=>'required|integer',
            'dinners_change'=>'required|integer',
            'money_change'=>'required|integer',
        ]); 
        
        if($request->input('breakfasts_change')<0 || $request->input('lunches_change')<0 || $request->input('dinners_change')<0)
        {
            return response(['message'=>'You can not remove negative amount of meals from card'],422);
        }
        if($request->input('money_change')!=0)
        {
            return response(['message'=>'You do not have permission to make transactions with money'],403);
        }
        $cardinfo=$this->cardInfo($request->input('card_id'));
        if($cardinfo['breakfasts_on_card']<$request->input('breakfasts_change') || $cardinfo['lunches_on_card']<$request->input('lunches_change') || $cardinfo['dinners_on_card']<$request->input('dinners_change'))
         return response(['message'=>"Student does not have enough meals. Breakfasts: {$cardinfo['breakfasts_on_card']} Lunches: {$cardinfo['lunches_on_card']} Dinners: {$cardinfo['dinners_on_card']}"],422);
        $transaction=new CardTransaction();
        $transaction->student_card_id=$request->input('card_id');
        $transaction->breakfasts_change=-$request->input('breakfasts_change');//minus zato sto prilikom trosenja obroka u menzi "skidamo" obroke sa kartice
        $transaction->lunches_change=-$request->input('lunches_change');
        $transaction->dinners_change=-$request->input('dinners_change');
        $transaction->money_change=$request->input('money_change');
        $transaction->executed_by_user_id=auth('api')->user()->id;
        $transaction->save();
        return response(['message'=>'Transaction created'],200);
        
    }
    /*
    Korisnik svakog meseca moze da kupi odredjeni broj obroka npr. 30 dorucka,30 rucka i 30 vecere,ako kojim slucajem ima
    obroka koji su mu preostali od proslog meseca mi broj obroka koje korisnik moze da kupi u toku meseca moramo da smanjimo
    za taj broj npr. ako su mu od prethodnih meseci ostala 2 dorucka,3 rucka i 1 vecera,a on u ovom mesecu maksimalno moze
    da kupi 30 dorucka,30 rucka,30 vecere,to znaci da mu je dozvoljeno da kupi jos 28 dorucka,27 rucka i 29 vecera.
    To radimo kako bi smo sprecili nagomilavanje oboroka.
    Ako bi smo proveru radili prilikom skidanja obroka sa kartice moglo bi da se da korisnik vec uzme rucak i kada dodje do kraja
    pokretne trake(posto se na kraju pokretne trake skidaju obroci sa kartice) da se ispostavi da on ne moze da uzme rucak(da je potrosio 
    kovotu za taj mesec) i onda bi on morao da vraca rucak.Zbog toga ovu proveru radimo prilikom kupovine obroka na kesomatu.
    */
    public function allowedNumberOfMeals($card_id)
    {
        $date_first=Carbon::today();
        $date_first->day=1;
        $date_first=$date_first->toDateTimeString();
        
        $meals_on_first_day_of_this_month=$mealsleft=CardTransaction::where('student_card_id',$card_id)->where('created_at','<',$date_first)->select(DB::raw('COALESCE(SUM(breakfasts_change),0) as breakfasts,COALESCE(SUM(lunches_change),0) as lunches,COALESCE(SUM(dinners_change),0) as dinners'))->first()->toArray();
        $meals_bought_this_month=CardTransaction::where('student_card_id',$card_id)->where('created_at','>=',$date_first)->select(DB::raw('SUM(CASE WHEN breakfasts_change>0 THEN breakfasts_change ELSE 0 END) as breakfasts,SUM(CASE WHEN lunches_change>0 THEN lunches_change ELSE 0 END) as lunches,SUM(CASE WHEN dinners_change>0 THEN dinners_change ELSE 0 END) as dinners'))->first()->toArray();
        //kupljena jela su zapisana kao pozitivan broj u tabeli,a potrosena kao negativan broj
        $card_type_info=StudentCard::where('student_cards.id',$card_id)->join('cards','cards.id','=','student_cards.card_type_id')->select('cards.breakfasts_per_month','cards.lunches_per_month','cards.dinners_per_month')->first();
        //return $card_type_info;
        foreach($meals_on_first_day_of_this_month as &$meal)
        {
            if(is_null($meal))
            $meal=0;
        }
        foreach($meals_bought_this_month as &$meal)
        {
            if(is_null($meal))
            $meal=0;
        }
        $meals['breakfasts']=$card_type_info['breakfasts_per_month'];
        $meals['lunches']=$card_type_info['lunches_per_month'];
        $meals['dinners']=$card_type_info['dinners_per_month'];
        foreach($meals as &$meal)
        {
            if(is_null($meal))
            $meal=Carbon::now()->daysInMonth;//ako je u tabeli NULL to znaci da korisnik ima obroke za svaki dan u nedelji
        }
       // return $meals;
        //ako mozemo u toku meseca da kupimo npr. 31 dorucak a pre toga smo vec imali 3 dorucka i kupili u toku ovog meseca 4 dorucka to znaci da mozemo da kupimo jos 24 dorucka
        $meals['breakfasts']-=($meals_on_first_day_of_this_month['breakfasts']+$meals_bought_this_month['breakfasts']);
        $meals['lunches']-=($meals_on_first_day_of_this_month['lunches']+$meals_bought_this_month['lunches']);
        $meals['dinners']-=($meals_on_first_day_of_this_month['dinners']+$meals_bought_this_month['dinners']);
        return $meals;
    //    return $meals_bought_this_month;
      //  return $meals_on_first_day_of_this_month;
    }
    public function createAtmCardTransaction(Request $request)
    {
        
     //   return $this->allowedNumberOfMeals($request->card_id);
        $request->validate([
            'card_id'=>['required','integer','exists:student_cards,id',function ($attribute, $value, $fail) use($request) {
                $card=StudentCard::where('student_cards.id',$request->input('card_id'))->join('cards','cards.id','student_cards.card_type_id')->select('student_cards.renewed_at','cards.validity_days')->first();
                $expires_at=Carbon::parse($card->renewed_at)->addDays($card->validity_days);
                $expired=$expires_at->lessThanOrEqualTo(Carbon::now());
                
                    if($expired)
                    {
                    $fail('Card expired.');
                    }
                }],
            'breakfasts_change'=>'required|integer',
            'lunches_change'=>'required|integer',
            'dinners_change'=>'required|integer',
            'money_change'=>'required|integer',
        ]); 

        if($request->input('breakfasts_change')<0 || $request->input('lunches_change')<0 || $request->input('dinners_change')<0  || $request->input('money_change')<0)
        {//posto ATM sluzi za kupovinu obroka tako da ne mozemo da imamo negativan broj obroka
            return response(['message'=>'You can not purchase negative number of meals and money must be a positive integer'],422);
        }
        $allowedNumOfMeals=$this->allowedNumberOfMeals($request->input('card_id'));
       // if($request->input('breakfasts_change')>$allowedNumOfMeals['breakfasts'])
        if($request->input('breakfasts_change')>$allowedNumOfMeals['breakfasts'] || $request->input('lunches_change')>$allowedNumOfMeals['lunches'] || $request->input('dinners_change')>$allowedNumOfMeals['dinners'])
        {
            return response(['message'=>"You already bought too many meals for this month.You can only buy: {$allowedNumOfMeals['breakfasts']} breakfasts, {$allowedNumOfMeals['lunches']} lunches, {$allowedNumOfMeals['dinners']} dinners."],422);
        }
        $moneyleft=CardTransaction::where('student_card_id',$request->input('card_id'))->select(DB::raw('COALESCE(SUM(money_change),0) as moneyLeft'))->first();
        $moneyleft=$moneyleft['moneyLeft'];
        $moneyleft+=$request->input('money_change');//ako korisnik uplati novac na karticu onda on ukupno ima novac_na_kartici+novi
        $mealprices=StudentCard::where('student_cards.id',$request->input('card_id'))->join('cards','student_cards.card_type_id','=','cards.id')->select('cards.breakfast_price as breakfast','cards.lunch_price as lunch','cards.dinner_price as dinner')->first();
        $bill=$request->input('breakfasts_change')*$mealprices['breakfast']+$request->input('lunches_change')*$mealprices['lunch']+$request->input('dinners_change')*$mealprices['dinner'];
        if($bill>$moneyleft)
        {
            $moneyneeded=$bill-$moneyleft;
            return response(['message'=>"You do not have enough money you need {$moneyneeded}"],422);
        }
        $transaction=new CardTransaction();
        $transaction->student_card_id=$request->input('card_id');
        $transaction->breakfasts_change=$request->input('breakfasts_change');
        $transaction->lunches_change=$request->input('lunches_change');
        $transaction->dinners_change=$request->input('dinners_change');
        $transaction->money_change=$request->input('money_change')-$bill;//prvo vrsimo proveru da li ima dovoljno novca promenjiva bill nam sadrzi ukupno cenu svih jela,ali ako u smo prilikom transakcije ubacili novac mi moramo od te sume da oduzmemo racun
        $transaction->executed_by_user_id=auth('api')->user()->id;
        $transaction->save();
        return response(['message'=>'Transaction created'],200);
        
    }
}
