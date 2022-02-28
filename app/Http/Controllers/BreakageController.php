<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Student;
use App\User;
use App\Dorm;
use App\Room;
use App\Breakage;
use App\DormWorker;
use Auth;

class BreakageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {
        //$user_type=Auth::user()->user_type;
        $user_id=Auth::user()->id;
        if(Auth::user()->isCraftsman() || Auth::user()->isClerical() || Auth::user()->isAdmin())
        {
            $workersdorms=Auth::user()->workPlaces();
          // dd($workersdorms);
            $breakages=Breakage::whereIn('breakages.dorm_id',$workersdorms)->join('dorms','breakages.dorm_id','=','dorms.id')->
            join('rooms','breakages.room_id','=','rooms.id')->
            select('breakages.*','rooms.room_number as room_number','dorms.name as dorm_name')->paginate(20);
        //    dd($breakages);
            return view('breakages.index')->with('breakages',$breakages);
        }
        else if(Auth::user()->isStudent())
        {
            $breakages=Breakage::where('reported_by_id','=',Auth::user()->id)->paginate(20);
        //    dd($breakages);
            return view('breakages.index')->with('breakages',$breakages);
        }
        else return abort(403);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $student=auth()->user()->student;
        if($student!=null  && $student->isDormTenant())
        {
        $dorm_name=$student->dorm->name;
        $room_number=$student->room->room_number;
        return view('breakages.create')->with('data',['dorm_name'=>$dorm_name,'room_number'=>$room_number]);
        }
        return abort(403);//dodaj nemate permisiju
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!Auth::user()->isStudent()) return abort(403);
   
             $this->validate($request,[
            'description' => ['required', 'string', 'max:1000','min:5'],
            ]);
            $student=auth()->user()->student;
            $breakage=new Breakage;
            $breakage->description=$request->input('description');
            $breakage->reported_by_id=$student->user_id;
            $breakage->dorm_id=$student->dorm_id;
            $breakage->room_id=$student->room_id;
            $breakage->status='unanswered';
            $breakage->save();
            return redirect('dashboard')->with('success','Breakage reportred.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $breakage=Breakage::findOrFail($id);
        $id=Auth::user()->id;
        $worksindorm=Auth::user()->worksInDorm($breakage->dorm_id);
        if($id==$breakage->reported_by_id || ($worksindorm && (Auth::user()->isCraftsman() || Auth::user()->isClerical() || Auth::user()->isAdmin())))
        return view('breakages.show')->with('breakage',$breakage);
        else return abort(403);

        
    }

    /**
     * Show the form for answering breakage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function answer($id)
    {
        $breakage=Breakage::find($id);
        $dorm_name=$breakage->dorm->name;
        $room_number=$breakage->room->room_number;
        $reported_by_user_name=$breakage->reported_by->name;
        $reported_by_user_surname=$breakage->reported_by->surname;
        $description=$breakage->description;
        $works_in_dorm=DormWorker::where('user_id','=',Auth::user()->id)->where('dorm_id','=',$breakage->dorm_id)->first();//realno ce proci kroz sve u bazi a nema potrebe dovoljno je cim nadje prvi record da stane
        if(Auth::user()->isCraftsman() && $works_in_dorm!=null && $breakage->status=='unanswered')
        {
        return view('breakages.answer')->with('data',['id'=>$id,'description'=>$description,'reported_by_user_name'=>$reported_by_user_name,'reported_by_user_surname'=>$reported_by_user_surname,'dorm_name'=>$dorm_name,'room_number'=>$room_number]);
        }
        else return abort(403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)//zapravo radi cuvanje answera
    {
        if(!Auth::check())//ako nije ulogovan
        return abort(403);//napravi neku stranicu tipa nemate pristup
        $breakage=Breakage::find($id,['dorm_id','status']);
        $works_in_dorm=Auth::user()->worksInDorm($breakage->dorm_id);
        if(!(Auth::user()->isCraftsman() && $works_in_dorm==true && $breakage->status=='unanswered'))
        {
        return abort(403);
        }
        $this->validate($request,[
            'answer' => ['required', 'string', 'max:1000','min:5'],
            'status'=>['required','string','max:255','in:solved,notsolved'],
            ]);
            $userid=0;
            $breakage=Breakage::find($id);
            $breakage->answer=$request->input('answer');
            $breakage->answered_by_id=auth()->user()->id;
            $breakage->status=$request->input('status');
            $breakage->save();
            return redirect('breakages')->with('success','Breakage report answered.');
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
