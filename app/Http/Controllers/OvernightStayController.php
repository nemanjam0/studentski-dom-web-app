<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Dorm;
use App\User;
use App\OvernightStay;
use Carbon\Carbon;//za datume

class OvernightStayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        
        $this->middleware(['CheckRole:admin,doorman,clerical']);
    }
    public function index()
    {
        $overnights=OvernightStay::whereIn ('overnight_stay.dorm_id',Auth::user()->workPlaces())->join('users as host','host.id','=','overnight_stay.host')->
        join('dorms','dorms.id','=','overnight_stay.dorm_id')->
        join('rooms','rooms.id','=','overnight_stay.room_id')->
        join('users as allowed_by','allowed_by.id','=','overnight_stay.allowed_by')->
        select('overnight_stay.*','host.name as host_name','host.surname as host_surname','dorms.name as dorm_name','rooms.room_number as room_number','allowed_by.name as allowed_by_name','allowed_by.surname as allowed_by_surname');
        if(Auth::user()->isDoorman())
        {
            $starts=Carbon::today()->subDays(3);//kako bi mogao da vidi 3 dana unapred prenocista
            $ends=Carbon::today()->addDays(3);//kako bi mogao da vidi prenocista kojakoja su se zavrsila pre max 3 dana
            $overnights=$overnights->where('check_in','<=',$ends)->where('check_out','>=',$starts)->get();
        }
        else $overnights=$overnights->get();
        
        //dd($overnights);
        return view('overnights.index')->with('overnights',$overnights);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dorms=Dorm::select('id','name','address')->whereIn('id',Auth::user()->workPlaces())->get();
        return view('overnights.create')->with('dorms',$dorms);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      //  if(!(Auth::user()->isAdmin() || Auth::user()->isClerical()))
       // return abort(403);
        $allowedovernights=3;
        $request->request->add(['check_in_out'=>'0']);//smisli elegantniji nacin
        $host=User::where('email',$request->input('host'))->where('user_type','student')->join('students','students.user_id','=','users.id')->where('students.dorm_tenant','=',1)->whereNotNull('students.dorm_id')->whereNotNull('students.room_id')->select('students.user_id','students.dorm_id','students.room_id')->first();//promeni email u username
        $this->validate($request,[
            'person_name'=>['required','string','min:8','max:50'],
            'id_number'=>['required','string','min:5','max:20'],
            'host'=>['required','string','exists:users,email',function ($attribute, $value, $fail) use($request,$host) {
                if ($host==null) {
                    $fail('Host is not a student or does not live in dorm.');
                }
                else if(!(Auth::user()->worksInDorm($host->dorm_id))){
                    $fail('You do not work in dorm in which student lives.');
                }
            }],//kada napravis kolonu username zameni mail u user name
            'check_out'=>['bail','required','date','after_or_equal:check_in'],//mora ovde kako bi koristili verifikovan datum u check_in proveru
            'check_in'=>['bail','required','date','after_or_equal:today','date','before_or_equal:check_out',function ($attribute, $value, $fail) use($request,$host) {
              //ovde dodaj validaciju za exceeded i pogledaj validator after
            }],
            'check_in_out'=>[function ($attribute, $value, $fail) use($request,$host,$allowedovernights) {
                if($host!=null)
               {
                $exceeded=OvernightStay::exceedovernights($host->user_id,new Carbon($request->input('check_in')),new Carbon($request->input('check_out')),$allowedovernights);
                
                if(!($exceeded=='' || $exceeded==null))
                {
                    $fail('Student exceded overnights for months:'.$exceeded);
                }
               }
            }],
            ]);
        
            $overnight=new OvernightStay;
            $overnight->person_name=$request->input('person_name');
            $overnight->id_number=$request->input('id_number');
            $overnight->host=$host->user_id;
            $overnight->dorm_id=$host->dorm_id;
            $overnight->room_id=$host->room_id;
            $overnight->check_in=$request->input('check_in');
            $overnight->check_out=$request->input('check_out');
            $overnight->allowed_by=Auth::id();
            $overnight->save();
            return redirect('dashboard')->with('success','Overnight stay created.');
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
        if(!(Auth::user()->isAdmin() || Auth::user()->isClerical()))
        return abort(403);
        OvernightStay::find($id)->delete();
        return redirect('overnights')->with('success','Overnight stay deleted.');
    }
}
