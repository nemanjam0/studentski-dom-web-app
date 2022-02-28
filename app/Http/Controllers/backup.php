<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Dorm;
use App\User;
use App\OvernightStay;
use Carbon\Carbon;//za datume

class OvernightStayControllerssassfd extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $allowedovernights=3;
        $host=User::where('email',$request->input('host'))->where('user_type','student')->join('students','students.user_id','=','users.id')->where('students.dorm_tenant','=',1)->whereNotNull('students.dorm_id')->whereNotNull('students.room_id')->select('students.dorm_id','students.room_id','students.user_id')->first();//promeni email u username
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
            'check_in'=>['bail','required','date','after_or_equal:today','date','before_or_equal:check_out',function ($attribute, $value, $fail) use($request,$allowedovernights) {
             
           // dd($request->input('check_in'));
            $date=Carbon::createFromFormat('Y-m-d', $request->input('check_in'));
            $firstday=$date;//pocetak meseca
            $lastday=$date;
            $firstday->day=1;
            $firstday=$firstday->format('Y-m-d');
            $lastday->day=$date->daysInMonth;
            $lastday=$lastday->format('Y-m-d');
            $overnights=OvernightStay::whereBetween('check_in', [$firstday, $lastday])->select('check_in','check_out')->get();
            $o=OvernightStay::exceedovernights(new Carbon($firstday),new Carbon($lastday));
            $numofnights=0;
            foreach($overnights as $night)
            {
                if($lastday<$night->check_out)//mozemo da upordjujemo stringove zato sto je format godina-mesec-dan
                {//ako je checkout date veci od zadnjeg dana u mesecu to znaci da je check out nekog drugog meseca a nas zanimaju samo dani u ovom mesecu,a ne u sledecim
                    $night->check_out=$lastday;//zbog toga check_out dodeljujemo kraj meseca kako bi prebrojali nocenja
                }
                $begindate=new Carbon($night->check_in);
               
                $enddate=new Carbon($night->check_out);
                
                $numofnights+=($begindate->diffInDays($enddate)+1);
            }
            $checkin=$request->input('check_in');
            $checkout=$request->input('check_out');
            $lastday=$date;
            $lastday->day=$date->daysInMonth;
            $lastday=$lastday->format('Y-m-d');
            if($checkout>$lastday)//ako je checkout u sledecem mesecu prebaci ga na kraj meseca zbog kalukalcije overnighta u mesecu
            $checkout=$lastday;
            $checkin=new Carbon($checkin);
            $checkout=new Carbon($checkout);
            $numofnights+=($checkin->diffInDays($checkout)+1);

            if($numofnights>$allowedovernights)
            {
                $fail('User exceeded number of overnights for that month.');
            }
            }],
            'check_out'=>['after_or_equal:check_in',function ($attribute, $value, $fail) use($request,$allowedovernights) {
             
                // dd($request->input('check_in'));
                 $date=Carbon::createFromFormat('Y-m-d', $request->input('check_out'));
                 $firstday=$date;//pocetak meseca
                 $lastday=$date;
                 $firstday->day=1;
                 $firstday=$firstday->format('Y-m-d');
                 $lastday->day=$date->daysInMonth;
                 $lastday=$lastday->format('Y-m-d');
                 $overnights=OvernightStay::whereBetween('check_out', [$firstday, $lastday])->select('check_in','check_out')->get();
                 $numofnights=0;
                 foreach($overnights as $night)
                 {
                     if($firstday>$night->check_in)//mozemo da upordjujemo stringove zato sto je format godina-mesec-dan
                     {//ako je checkout date veci od zadnjeg dana u mesecu to znaci da je check out nekog drugog meseca a nas zanimaju samo dani u ovom mesecu,a ne u sledecim
                         $night->check_in=$firstday;//zbog toga check_out dodeljujemo kraj meseca kako bi prebrojali nocenja
                     }
                     $begindate=new Carbon($night->check_in);
                     $enddate=new Carbon($night->check_out);
                     $numofnights+=($begindate->diffInDays($enddate)+1);
                 }
                 $checkin=$request->input('check_in');
                 $checkout=$request->input('check_out');
                 $firstday=$date;
                 $firstday->day=1;
                 $firstday=$firstday->format('Y-m-d');
                 if($checkin<$firstday)//ako je checkout u sledecem mesecu prebaci ga na kraj meseca zbog kalukalcije overnighta u mesecu
                 $checkin=$firstday;
                 $checkin=new Carbon($checkin);
                 $checkout=new Carbon($checkout);
                 $numofnights+=($checkin->diffInDays($checkout)+1);
     
                 if($numofnights>$allowedovernights)
                 {
                     $fail('User exceeded number of overnights for that month.');
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
        //
    }
}
