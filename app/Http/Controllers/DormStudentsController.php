<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Dorm;
use App\Room;
use App\User;
use App\Student;
use DB;

class DormStudentsController extends Controller
{
    /**
     * Referent moze da prebacuje studente samo u okviru doma koje on kontrolise
     * Prebacivanje studenata iz bilo kog doma u bilo koji dom moze da radi samo admin
     */
     /** Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        
        $this->middleware(['CheckRole:admin,clerical']);//samo admin i referent mogu da pristupe ovom delu 
    }
    public function index()
    {
        $dormstudents=DB::table('students')->whereNotNull('students.dorm_id')->whereNotNull('students.room_id')->join('rooms','students.room_id','=','rooms.id')->join('dorms','students.dorm_id','=','dorms.id')->join('users','users.id','=','students.user_id')->select('students.id as student_id','users.name as student_name','users.surname as student_surname','dorms.name as dorm_name','rooms.room_number as room_number')->paginate(20);
        //dd($dormstudents);
        return view('dormstudents.index')->with('dormstudents',$dormstudents);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dorms=Dorm::select('id','name')->whereIn('id',Auth::user()->workPlaces())->get();
        return view('dormstudents.create')->with('dorms',$dorms);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,['dorm' => ['required','exists:dorms,id',function ($attribute, $value, $fail) use($request) {
            if (!($request->user()->worksInDorm($request->input('dorm')))) {
                $fail('You do not work in selected dorm.');
            }}]]);
            
        $room=Room::where('dorm_id',$request->input('dorm'))->where('room_number',$request->input('room_number'))->select('id','room_capacity')->first();//ne postoji vise od jedne sobe
        if($room!=null)//ako je soba nadjena
        $roomid=$room->id;
        else $roomid=-1;
        $user=User::with(['student'])->where('email',$request->input('username'))->first();// podrazumeva se da samo jedan postoji zato sto je email/username unique
        $this->validate($request,[
            'room_number' =>['bail','required','string','max:20',function ($attribute, $value, $fail) use($request) {
                $room=Room::where('dorm_id',$request->input('dorm'))->where('room_number',$request->input('room_number'))->count();
                if ($room<1) {
                    $fail('Room with that number does not exists in that dorm.');
                }
            },function ($attribute, $value, $fail) use($request,$roomid,$room) {
                $studentsinroom=Student::where('room_id',$roomid)->where('dorm_tenant','=',1)->count();//realno dorm_tenant je bespotreban
                if (($studentsinroom+1)>$room->room_capacity){//+1 zato sto dodajemo studenta 
                    $fail('That room is already full.');
                    }
                }
            ],
            'username'=>['required','string','exists:users,email',function ($attribute, $value, $fail) use($request) {
                $user=User::where('email',$request->input('username'))->where('user_type','student')->count();
                if ($user<1) {
                    $fail('User is not a student.');
                }
            },function ($attribute, $value, $fail) use($request) {
                $student=User::where('email',$request->input('username'))->where('user_type','student')->join('students','students.user_id','=','users.id')->whereNull('students.dorm_id')->whereNull('students.room_id')->where('students.dorm_tenant','=',0)->count();
                if ($student<1) {
                    $fail('Student already lives in dorm.');
                }
            }]//kada napravis kolonu username zameni ovo
            ]);
            $roomid=($room=Room::where('dorm_id',$request->input('dorm'))->where('room_number',$request->input('room_number'))->select('id')->first())->id;//ne postoji vise od jedne sobe
            $user->student->dorm_id=$request->input('dorm');
            $user->student->room_id=$roomid;
            $user->student->dorm_tenant=1;
            $user->student->save();
            return redirect('dormstudents')->with('success','Student added to dorm.');
            
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
        $dormstudent=Student::where('students.id','=',$id)->join('dorms','dorms.id','=','students.dorm_id')->join('rooms','rooms.id','=','students.room_id')->join('users','users.id','=','students.user_id')->select('students.id as id','users.email as email','users.name as user_name','users.surname as user_surname','rooms.room_number as room_number','dorms.id as dorm_id')->first();//first zato sto je students.id primary key tako da ce u bazi postojati samo jedan record
        if($dormstudent==null) return abort(404);//napravi neku drugu poruku ako ne nadje tog studenta u bazi
        //if(!(Auth::user()->worksInDorm($dormstudent->dorm_id)))
        //return abort(403);
       // dd($dormstudent);
       $dorms=Dorm::select('id','name')->whereIn('id',Auth::user()->workPlaces())->get();
      // dd($dorms);
        return view('dormstudents.edit')->with('data',['dormstudent'=>$dormstudent,'dorms'=>$dorms]);
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
        $room=Room::where('dorm_id',$request->input('dorm'))->where('room_number',$request->input('room_number'))->select('id','room_capacity')->first();//ne postoji vise od jedne sobe
        $dormstudent=Student::find($id);// podrazumeva se da samo jedan postoji zato sto je email/username unique
        if($room!=null)//ako je soba nadjena
        $roomid=$room->id;
        else $roomid=-1;//ako objekat $room ne postoji to znaci da takva soba ne postoji u domu zato se $roomid setuje na -1 validator zatim izbacuje gresku da soba ne postoji
        $this->validate($request,[
            'dorm' => ['required','exists:dorms,id',function ($attribute, $value, $fail) use($request) {
                if (!($request->user()->worksInDorm($request->input('dorm')))) {
                    $fail('You do not work in selected dorm.');
                }
                }],
            'room_number' =>['required','string','max:20',function ($attribute, $value, $fail) use($request,$roomid,$dormstudent,$room) {
                if ($roomid==-1) {
                    $fail('Room with that number does not exists in that dorm.');
                }
                else if($roomid!=$dormstudent->room_id) {
                    $studentsinroom=Student::where('room_id',$roomid)->where('dorm_tenant','=',1)->count();
                    if (($studentsinroom+1)>$room->room_capacity){
                    $fail('That room is already full.');
                    }
                }
            }],
            ]);
            $dormstudent->dorm_id=$request->input('dorm');
            $dormstudent->room_id=$roomid;
            $dormstudent->dorm_tenant=1;
            $dormstudent->save();
            return redirect('dormstudents')->with('success','Changes saved.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $student=Student::findOrFail($id);
        $user_id=$student->user()->first()->id;
        $workplaces=Auth::user()->workPlaces();
        //dd($user_id);
        if(!(in_array($student->dorm_id,$workplaces)))
        return abort(403);
        $cnt=DB::table('loaned_items')->where('borrower_id',$user_id)->sum('quantity');
        //dd($cnt);
        if($cnt>0)//znaci da nije vratio sve stvari
        return redirect()->back()->with('error','Student did not return all borrowed items!');
        $student->dorm_id=null;
        $student->room_id=null;
        $student->dorm_tenant=0;
        $student->save();
        return redirect()->back()->with('success','Student removed from the dorm.');
        
    }
}
