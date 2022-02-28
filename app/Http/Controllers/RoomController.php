<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Room;
use App\Dorm;
use App\Student;
use Illuminate\Validation\Rule;
use DB;

class RoomController extends Controller
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
        $rooms=DB::table('rooms')->join('dorms','rooms.dorm_id','=','dorms.id')->leftJoin('students','students.room_id','=','rooms.id')->select(DB::raw('COUNT(students.room_id) as students_in_room'),'rooms.id as room_id','dorms.name as dorm_name','rooms.room_number as room_number','rooms.room_capacity as room_capacity','rooms.category as category')->groupBy('rooms.id')->get();
        return view('rooms.index')->with('rooms',$rooms);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dorms=Dorm::all();
        return view('rooms.create')->with('dorms',$dorms);
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
            'dorm' => ['required','exists:dorms,id'],
            'room_number' =>['required','string','max:20',function ($attribute, $value, $fail) use($request) {
                $room=Room::where('dorm_id',$request->input('dorm'))->where('room_number',$request->input('room_number'))->count();
                if ($room>0) {
                    $fail('Room with that number already exists in that dorm.');
                }
            }],
            'room_capacity'=>['required','integer','max:20'],//mozda treba tinyInteger proveri!!!
            'floor'=>['required','integer','max:30'],
            'room_description'=>['nullable','string','max:300'],//nadji kolki je max za mediumtext kad proradi net
            'category'=>['nullable','integer','min:1'],
            ]);
         
            $room=new Room;
            $room->dorm_id=$request->input('dorm');
            $room->room_number=$request->input('room_number');
            $room->room_capacity=$request->input('room_capacity');
            $room->floor=$request->input('floor');
            $room->description=$request->input('room_description');
            $room->category=$request->input('category');
            $room->save();
            return redirect('dashboard')->with('success','Room created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $room=Room::find($id);
        $students=Student::join('users','users.id','=','students.user_id')->where('students.room_id','=',$id)->join('colleges','colleges.id','=','students.college_id')->select('students.id as student_id','users.name as student_name','users.surname as student_surname','students.year_of_study as year','colleges.name as college_name')->get();
        $dorm=Dorm::find($room->dorm_id);
      //  dd($students);
        return view('rooms.show')->with('data',['dorm'=>$dorm,'room'=>$room,'students'=>$students]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $room=Room::find($id);
        $dorm=Dorm::where('id',$room->dorm_id)->first();
       // DB::enableQueryLog();
      //  $students=Student::where('students.room_id','=',$id)->with('user')->get(); // prouci sta je  bolje
        $students=Student::where('students.room_id','=',$id)->join('users','users.id','=','students.id')->join('colleges','colleges.id','=','students.college_id')->select('users.name as student_name','users.surname as student_surname','colleges.name as college_name','students.id as student_id','students.year_of_study as year')->get();
        //dd(DB::getQueryLog());
      //  dd($students);
        return view('rooms.edit')->with('data',['room'=>$room,'dorm'=>$dorm,'students'=>$students]);
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
            //'dorm' => ['required','exists:dorms,id'],
            'room_number' =>['required','string','max:20',function ($attribute, $value, $fail) use($request,$id) {
                $rooms=Room::where('dorm_id',$request->input('dorm'))->where('room_number',$request->input('room_number'))->get();
                $numberOfRooms=0;
                $someroom=0;
                foreach($rooms as $room)
                {
                    $numberOfRooms++;
                    $someroom=$room;
                }
                if ($numberOfRooms>0) {
                    if($numberOfRooms>1 || ($numberOfRooms==1 && $someroom->id!=$id))//ako je broj nadjenih soba 1,a id te sobe se razlikuje od id-ja trenutne to znaci da soba sa tim brojem vec postoji u tom domu,tako da ne mozemo dati isti broj sobe
                    {
                    $fail('Room with that number already exists in that dorm.');
                    }
                }
            }],
            'room_capacity'=>['required','integer','max:20',function ($attribute, $value, $fail) use($request,$id) {
                $numofstudents=Student::where('room_id','=',$id)->count();//room_id je foreign key za id sobe tako da nema potrebe da proveravamo pored toga i dorm_id posto je room_id unikatan
              
                if($numofstudents>$request->input('room_capacity'))
                $fail('There are more students in the room.');
            }],//mozda treba tinyInteger proveri!!!
            'floor'=>['required','integer','max:30'],
            'room_description'=>['nullable','string','max:300'],//nadji kolki je max za mediumtext kad proradi net
            'category'=>['nullable','integer','min:1'],
            ]);
            $room=Room::find($id);
            //$room->dorm_id=$request->input('dorm');
            $room->room_number=$request->input('room_number');
            $room->room_capacity=$request->input('room_capacity');
            $room->floor=$request->input('floor');
            $room->description=$request->input('room_description');
            $room->category=$request->input('category');
            $room->save();
            return redirect('rooms')->with('success','Changes saved.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Room::find($id)->delete();
        return redirect('rooms')->with('success','Room deleted.');
    }
}
