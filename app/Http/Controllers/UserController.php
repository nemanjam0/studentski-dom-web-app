<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use App\Invoices\InvoiceLog;
use App\User;
use Carbon\Carbon;
use Auth;
use DB;

class UserController extends Controller
{
    protected   $roles = [
        'admin', 'craftsman','clerical','laundryman','doorman','atm','cafeteria_worker'
    ];
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    //private $roles=array("admin,referent");
    public function __construct()
    {
        
       $this->middleware(['CheckRole:admin'])->except('dashboard');//samo admin moze da pristupi ovom delu
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users=User::where('user_type','<>','student')->get();
        //dd($users);
        return view('users.index')->with('users',$users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create')->with('roles',$this->roles);
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
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'birthday_date' => ['required', 'date'],//
        'user_type'=>['required','not_in:student',Rule::in($this->roles)],
        ],['user_type.not_in'=>'This is not form for creating students']);
        $cstrong=true;
        $bytes = openssl_random_pseudo_bytes(4, $cstrong);//https://www.php.net/manual/en/function.openssl-random-pseudo-bytes.php
        $pw = bin2hex($bytes);
       // dd($pw);
        $user=new User;
        $user->name=$request->input('name');
        $user->middle_name=$request->input('middle_name');
        $user->surname=$request->input('surname');
        $user->email=$request->input('email');
        $user->password=Hash::make($pw);
        $user->temp_pass=$pw;
        $user->birthday_date=$request->input('birthday_date');
        $user->user_type=$request->input('user_type');
        $user->save();
        return redirect('dashboard')->with('success','User creadted.');
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
       // User::find($id)->delete();
    }
    public function dashboard()
    {
     

        if(!Auth::check())//ako nije ulogovan
        return redirect()->route('login');//napravi neku stranicu tipa nemate pristup
       // $user_type=Auth::user()->user_type;
        if(Auth::user()->isAdmin())
        {
            $day_period=7;
            $enddate=Carbon::now()->startOfDay()->toDateTimeString();
            //dd($enddate);
            $startdate=Carbon::today()->subDays($day_period)->toDateTimeString();
            $successful_count=InvoiceLog::where('started','>=',$startdate)->where('started','<=',$enddate)->selectRaw('count(distinct (case when successful = 1 then DATE(started) end)) as successful_count')->pluck('successful_count')->first();
            $error=0;
            //  $logs=InvoiceLog::where('started','>=',$startdate)->where('started','<=',$enddate)->get();
            if($successful_count<$day_period)
            $error="Invoices were not send everyday or there was error in past $day_period days.";
          //  dd($logs);
          //  dd($error);
            return view('dashboards.admin')->with('invoices_error',$error);
        }
        else if(Auth::user()->isCraftsman())
        {
            return view('dashboards.craftsman');  
        }
        else if(Auth::user()->isStudent())
        {
            return view('dashboards.student');
        }
        else if(Auth::user()->isClerical())
        {
            return view('dashboards.clerical');
        }
        else if(Auth::user()->isLaundryman())
        {
            return view('dashboards.laundryman');
        }
        else if(Auth::user()->isDoorman())
        {
            return view('dashboards.doorman');
        }
        else return abort(403);
    }
}
