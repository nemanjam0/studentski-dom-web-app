<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\User;
use App\Student;
use App\LoanedItem;
use Auth;
use Carbon\Carbon;
use DB;
use Hash;

class LoanedItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        
       $this->middleware(['CheckRole:admin,laundryman'])->except('show_students_items');
       $this->middleware(['CheckRole:admin,laundryman,student'])->only('show_students_items');
    }
    public function index()
    {
        $loaned_items=LoanedItem::join('items','items.id','=','loaned_items.item_id')->join('users AS borrower_user','loaned_items.borrower_id','=','borrower_user.id')->join('users AS borrowed_by_user','loaned_items.borrowed_by_id','=','borrowed_by_user.id')->join('students','students.user_id','=','borrower_user.id')->leftJoin('dorms','students.dorm_id','=','dorms.id')->leftJoin('rooms','students.room_id','=','rooms.id')->select('borrower_user.id as borrower_user_id','borrower_user.name as borrower_name','borrower_user.surname as borrower_surname','dorms.name as dorm_name','rooms.room_number as room_number','items.name as item_name','borrowed_by_user.name as borrowed_by_name','borrowed_by_user.surname as borrowed_by_surname','loaned_items.quantity as quantity')->orderBy('loaned_items.created_at')->paginate(20);
       // dd($loaned_items);
        return view('borrowing.index')->with('loaned_items',$loaned_items);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }
    public function borrowing($userid)
    {
        $items=Item::all();
        $student=Student::where('user_id',$userid)->join('users','students.user_id','=','users.id')->join('dorms','students.dorm_id','=','dorms.id')->join('rooms','students.room_id','=','rooms.id')->select('users.id as user_id','users.name as user_name','users.surname as user_surname','dorms.name as dorm_name','rooms.room_number as room_number')->first();
       // dd($student->dorm_name);
        if($student==null || is_null($student->dorm_name)) return redirect()->route('dashboard')->with('error','Student does not live in dorm.');
       // dd($items);
        return view('borrowing.borrowing')->with('data',['type'=>'borrowing','items'=>$items,'student'=>$student]);
    }
    public function returning($userid)
    {
        if(Auth::user()->isStudent())
        return abort(403);
        $items=Item::all();
        $student=Student::where('user_id',$userid)->join('users','students.user_id','=','users.id')->join('dorms','students.dorm_id','=','dorms.id')->join('rooms','students.room_id','=','rooms.id')->select('users.id as user_id','users.name as user_name','users.surname as user_surname','dorms.name as dorm_name','rooms.room_number as room_number')->first();
       // dd($student->dorm_name);
        if($student==null || is_null($student->dorm_name)) return redirect()->route('dashboard')->with('error','Student does not live in dorm.');
       // dd($items);
        return view('borrowing.returning')->with('data',['type'=>'returning','items'=>$items,'student'=>$student]);
    }

    /**
     * Borrowed a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function borrow(Request $request)
    {
        $this->validate($request,[
            'item.*'=>['nullable','integer','min:0'],
            'password'=>['required',function ($attribute, $value, $fail) use($request) {
               $hashedpw=User::where('users.id',$request->input('user_id'))->pluck('password')->first();
               if (!Hash::check($request->input('password'), $hashedpw)) {
                $fail('Wrong password');
            }
            }]
            ],
            ['item.*.integer'=>'The quantity must be an integer.','item.*.min'=>'The quantity must be an at least 0.']);
        $items=[];
        $timenow=Carbon::now();
        foreach($request->input('item') as $key => $value)
        {
            if($value!=0)//nema razloga upisivati da je korisnik pozajmio 0 stvari
            {
                $items[]=[
                'borrower_id'=>$request->input('user_id'),
                'borrowed_by_id'=>Auth::id(),
                'item_id'=>$key,
                'quantity'=>$value,
                'created_at'=>$timenow,
                'updated_at'=>$timenow,
                ];
            }
        }
        LoanedItem::insert($items);
        return redirect()->route('dashboard')->with('success','Items borrowed successfully.');
    }
      /**
     * Returned a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function return(Request $request)
    {
        $haveitems=DB::table('items')->join('loaned_items', function($join) use($request)
        {
            $join->on('items.id','=','loaned_items.item_id')->where('loaned_items.borrower_id',$request->input('user_id'));
           
        })->select(DB::raw('SUM(loaned_items.quantity) as num_of_items'),'items.id as item_id')->groupBy('items.id')->get();
        $borroweditems=[];
        foreach($haveitems as $item)
        {
            $borroweditems[$item->item_id]=$item->num_of_items;
        }
       // dd($borroweditems);
        $this->validate($request,[
            'item.*'=>['nullable','integer','min:0',function ($attribute, $value, $fail) use($request,$borroweditems) {
                $pieces = explode(".", $attribute);
                //item.2 posle expode dobijamo niz pieces[0]=item,pieces[1]=2; 2 nam predstavlja primary key u items koloni
                $exists=array_key_exists($pieces[1],$borroweditems);
                if($exists==true)
                {
                    if ($borroweditems[$pieces[1]]-$value<0) {
                        $fail('Student did not borrowed that many items.');
                    }
                }
                else if($value!=0)//ako key ne posti to znaci da korisnik nije pozajmio item sa tim indeksom tako da ni ne moze da ga vrati
                {
                    $fail('Student did not borrowed that many items.');
                }
            }],
        ],['item.*.integer'=>'The quantity must be an integer.','item.*.min'=>'The quantity must be an at least 0.']);
        $items=[];
        $timenow=Carbon::now();
        foreach($request->input('item') as $key => $value)
        {
            if($value!=0)
            {
                $items[]=[
                'borrower_id'=>$request->input('user_id'),
                'borrowed_by_id'=>Auth::id(),
                'item_id'=>$key,
                'quantity'=>-$value,
                'created_at'=>$timenow,
                'updated_at'=>$timenow,
                ];
            }
        }
        LoanedItem::insert($items);
        return redirect()->route('dashboard')->with('success','Items returned successfully.');
    }
    public function show($id)
    {
        
    }
    /**
     * Display items that student borrowed
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_students_items($user_id)
    {
        if(!(Auth::user()->isLaundryman() || Auth::user()->id==$user_id))
        {
            return abort(403);
        }
        if(Auth::user()->isLaundryman())
        $error_msg="Student does not live in dorm.";
        else $error_msg="You do not live dorm.";
        $student=User::where('users.id',$user_id)->join('students','students.user_id','=','users.id')->join('dorms','dorms.id','=','students.dorm_id')->join('rooms','rooms.id','=','students.room_id')->select('users.name as user_name','users.surname as user_surname','dorms.name as dorm_name','rooms.room_number as room_number')->first();
        if($student==null || is_null($student->dorm_name)) return redirect()->route('dashboard')->with('error',$error_msg);
        $loaned_items=LoanedItem::where('borrower_id',$user_id)->join('items','items.id','=','loaned_items.item_id')->join('users','loaned_items.borrowed_by_id','=','users.id')->select('items.name as name','loaned_items.quantity as quantity','loaned_items.created_at as created_at','users.name as borrowed_by_name','users.surname as borrowed_by_surname')->get();
        $haveitems=DB::table('items')->join('loaned_items', function($join) use($user_id)
        {
            $join->on('items.id','=','loaned_items.item_id')->where('loaned_items.borrower_id',$user_id);
           
        })->select(DB::raw('SUM(loaned_items.quantity) as num_of_items'),'items.name as name','items.id as id')->groupBy('items.id')->get();
        return view('borrowing.show_students_items')->with('data',['items'=>$haveitems,'student'=>$student,'loaned_items'=>$loaned_items]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
