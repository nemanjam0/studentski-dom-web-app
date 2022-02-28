<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Student;
use App\College; 
use App\Dorm;
use App\Room;

use Illuminate\Validation\Rule;
use Carbon\Carbon;//za datume
use Auth;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        
        $this->middleware(['CheckRole:admin,clerical']);//samo admin i referent mogu da pristupe ovom delu 
    }
    public function index()
    {
        $workplaces=Auth::user()->workPlaces();
        //dd($workplaces);
        $students=Student::whereIn('students.dorm_id',$workplaces)->orWhereNull('students.dorm_id')->with(['user:id,name,middle_name,surname','college:id,name','dorm:id,name','room:id,room_number'])->paginate(20);
        return view('users.students.index')->with('students',$students);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $colleges=College::all();
        return view('users.students.create')->with('colleges',$colleges);
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
            'name' => ['required', 'string', 'max:255'],
            'middle_name' => ['string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'birthday_date' => ['required', 'date'],//
            'college'=>['required','integer','exists:colleges,id'],
            'year_of_study'=>['required','integer','between:1,9'],//realno ne moze da bude veca od 9(4+1+3+1(produzena)) zato sto studenti koji su obnavljali godinu nemaju pravo na karticu i dom
            'br_indeksa'=>['required','string','max:20'],
            'finance_status'=>['required','string','max:255','in:budget,self-financing'],// mozda ne treba string ako vec postoji in
            ]);
            $date=Carbon::createFromFormat('Y-m-d', $request->input('birthday_date'));
            $pw=$date->format('dmY');
            $user=new User;
            $user->name=$request->input('name');
            $user->middle_name=$request->input('middle_name');
            $user->surname=$request->input('surname');
            $user->email=$request->input('email');
            $user->password=Hash::make($pw);
            $user->temp_pass=$pw;
            $user->birthday_date=$request->input('birthday_date');
            $user->user_type='student';

            $student=new Student;
            $student->college_id=$request->input('college');
            $student->year_of_study=$request->input('year_of_study');
            $student->indeks=$request->input('br_indeksa');
            $student->finance_status=$request->input('finance_status');
            $user->save();
            $userid=$user->id;
            $student->user_id=$userid;
            $student->save();
            return redirect('dashboard')->with('success','Student created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $student=Student::where('students.id','=',$id)->join('users','users.id','students.user_id')->join('colleges','colleges.id','=','students.college_id')->join('dorms','dorms.id','=','students.dorm_id')->join('rooms','rooms.id','=','students.room_id')->select('users.name as student_name','users.middle_name','users.surname','users.email','users.birthday_date','students.*','colleges.name as college_name','dorms.name as dorm_name','rooms.room_number')->first();
        
        //dd($student);
        return view('users.students.show')->with('student',$student);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $student=Student::where('students.id','=',$id)->join('users','users.id','students.user_id')->leftJoin('rooms','rooms.id','=','students.room_id')->select('users.name as student_name','users.middle_name','users.surname','users.email','users.birthday_date','students.*','rooms.room_number')->first();
        $workplaces=Auth::user()->workPlaces();
        if(!(in_array($student->dorm_id,$workplaces) || is_null($student->dorm_id)))
        return abort(403);
        $colleges=College::all();
        $dorms=Dorm::all();
        //dd($student);
        return view('users.students.edit')->with('data',['student'=>$student,'colleges'=>$colleges,'dorms'=>$dorms]);
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
        $workplaces=Auth::user()->workPlaces();
        $student=Student::find($id);
        if(!(in_array($student->dorm_id,$workplaces) || is_null($student->dorm_id)))
        return abort(403);
       // dd($request->dorm);
        //copy-paste iz DormStudentsControler/update
        $room=Room::where('dorm_id',$request->input('dorm'))->where('room_number',$request->input('room_number'))->select('id','room_capacity')->first();//ne postoji vise od jedne sobe
        if($room!=null)//ako je soba nadjena
        $roomid=$room->id;
        else $roomid=-1;//ako objekat $room ne postoji to znaci da takva soba ne postoji u domu zato se $roomid setuje na -1 validator zatim izbacuje gresku da soba ne postoji
        $this->validate($request,[
            'name' => ['required', 'string', 'max:255'],
            'middle_name' => ['string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', function ($attribute, $value, $fail) use($request,$id,&$student) {
                $user=User::where('email',$request->input('email'))->first();//first zato sto se podrazumeva da je samo jedan user s tim mailom,ako koristimo get dobicemo array korisnika koji sadrzi samo jednog korisnika
                if($user->id!=$student->user_id)
                {
                    $fail("That email is used by another user.");
                }
                 
             }],
            'birthday_date' => ['required', 'date'],//
            'college'=>['required','integer','exists:colleges,id'],
            'year_of_study'=>['required','integer','between:1,9'],//realno ne moze da bude veca od 9(4+1+3+1(produzena)) zato sto studenti koji su obnavljali godinu nemaju pravo na karticu i dom
            'br_indeksa'=>['required','string','max:20'],
            'finance_status'=>['required','string','max:255','in:budget,self-financing'],// mozda ne treba string ako vec postoji in
            'dorm' => ['nullable','exists:dorms,id',function ($attribute, $value, $fail) use($request) {
                if (!($request->user()->worksInDorm($request->input('dorm')))) {
                    $fail('You do not work in selected dorm.');
                }
                }],
            'room_number' =>['max:20',function ($attribute, $value, $fail) use($request,$roomid,$student,$room) {
           // dd($roomid);
                if(is_null($request->input('dorm')) && !is_null($request->input('room_number')))
                {
                    $fail('You need to choose dorm.');
                }
                elseif ($roomid==-1) {
                    $fail('Room with that number does not exists in that dorm.');
                }
                else if($roomid!=-1) {
                    $studentsinroom=Student::where('room_id',$roomid)->where('dorm_tenant','=',1)->count();
                   // dd($roomid!=$student->room_id);
                    if ((($studentsinroom+1)>$room->room_capacity)&& $roomid!=$student->room_id){
                    $fail('That room is already full.');
                    }
                }
            }],
            ]);
            $user=User::find($student->user_id);
            $user->name=$request->input('name');
            $user->middle_name=$request->input('middle_name');
            $user->surname=$request->input('surname');
            $user->email=$request->input('email');
            $user->birthday_date=$request->input('birthday_date');
            $user->save();
            //$student=Student::find($id);//vec je setovano gore
            $student->college_id=$request->input('college');
            $student->year_of_study=$request->input('year_of_study');
            $student->indeks=$request->input('br_indeksa');
            $student->finance_status=$request->input('finance_status');
           
            $student->dorm_id=$request->input('dorm');
            if($roomid==-1)//znaci da soba nije nadjena tj. treba setovati na null
            $student->room_id=null;
            else
            $student->room_id=$roomid;
            $student->dorm_tenant=1;
            $student->save();
            return redirect('students')->with('success','Changes saved');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $workplaces=Auth::user()->workPlaces();
        $student=Student::find($id);
        if(!(in_array($student->dorm_id,$workplaces) || is_null($student->dorm_id)))
        return abort(403);
        User::find($student->user_id)->delete();
        $student->delete();
        return redirect()->route('students.index')->with('success','Student deleted');
    }
}
