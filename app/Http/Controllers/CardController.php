<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Card;

class CardController extends Controller
{

    public function __construct()
    {
        
        $this->middleware(['CheckRole:admin']);//samo admin moze da pristupe ovom delu 
    }
    public function index()
    {
        $cards=Card::all();
        return view('cards.index')->with('cards',$cards);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cards.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => ['required', 'string', 'max:20','unique:cards'],
            'breakfasts_per_month' => ['nullable', 'integer'],
            'lunches_per_month' => ['nullable', 'integer'],
            'dinners_per_month' => ['nullable', 'integer'],
            'breakfasts_per_day' => ['nullable', 'integer'],
            'lunches_per_day' => ['nullable', 'integer'],
            'dinners_per_day' => ['nullable', 'integer'],
            'breakfast_price' => ['min:0', 'integer'],
            'lunch_price' => ['min:0', 'integer'],
            'dinner_price' => ['min:0', 'integer'],
            'description' => ['string', 'max:100'],
            'validity_days' => ['nullable', 'integer'],
            ]);
        $card=new Card;
        $card->name=$request->input('name');
        $card->breakfasts_per_month=$request->input('breakfasts_per_month');
        $card->lunches_per_month=$request->input('lunches_per_month');
        $card->dinners_per_month=$request->input('dinners_per_month');
        $card->breakfasts_per_day=$request->input('breakfasts_per_day');
        $card->lunches_per_day=$request->input('lunches_per_day');
        $card->dinners_per_day=$request->input('dinners_per_day');
        $card->breakfast_price=$request->input('breakfast_price');
        $card->lunch_price=$request->input('lunch_price');
        $card->dinner_price=$request->input('dinner_price');
        $card->validity_days=$request->input('validity_days');
        $card->description=$request->input('description');
        $card->save();
        return redirect()->route('dashboard')->with('success','New card type created.');
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
        $card=Card::find($id);
        if($card==null) return redirect()->route('cards.index')->with('error','Card with that id does not exist in database.');
        return view('cards.edit')->with('card',$card);
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
            'name' => ['required', 'string', 'max:20',Rule::unique('cards')->ignore($id, 'id')],//kada updatujemo ako ne stavimo ignore ono ce prepoznati da kartica sa tim imenom postoji i nece nam dati da vrsimo izmenu ako NE menjamo ime kartice
            'breakfasts_per_month' => ['nullable', 'integer'],
            'lunches_per_month' => ['nullable', 'integer'],
            'dinners_per_month' => ['nullable', 'integer'],
            'breakfasts_per_day' => ['nullable', 'integer'],
            'lunches_per_day' => ['nullable', 'integer'],
            'dinners_per_day' => ['nullable', 'integer'],
            'breakfast_price' => ['min:0', 'integer'],
            'lunch_price' => ['min:0', 'integer'],
            'dinner_price' => ['min:0', 'integer'],
            'description' => ['string', 'max:100'],
            'validity_days' => ['nullable', 'integer'],
            ]);
            $card=Card::find($id);
            $card->name=$request->input('name');
            $card->breakfasts_per_month=$request->input('breakfasts_per_month');
            $card->lunches_per_month=$request->input('lunches_per_month');
            $card->dinners_per_month=$request->input('dinners_per_month');
            $card->breakfasts_per_day=$request->input('breakfasts_per_day');
            $card->lunches_per_day=$request->input('lunches_per_day');
            $card->dinners_per_day=$request->input('dinners_per_day');
            $card->breakfast_price=$request->input('breakfast_price');
            $card->lunch_price=$request->input('lunch_price');
            $card->dinner_price=$request->input('dinner_price');
            $card->validity_days=$request->input('validity_days');
            $card->description=$request->input('description');
            $card->save();
            return redirect()->route('cards.index')->with('success','Changes saved.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Card::find($id)->delete();
        //return redirect('cards')->with('success','Card deleted');
    }
}
