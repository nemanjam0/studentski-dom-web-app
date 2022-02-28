<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Dorm;
use App\DormWorker;
use Illuminate\Validation\Rule;
use DB;

class DormWorkersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected  $user_types = [
        'admin', 'craftsman','clerical','laundryman','doorman','atm','cafeteria_worker'
    ];
    public function __construct()
    {
        
        $this->middleware(['CheckRole:admin']);//samo admin moze da pristupe ovom delu 
    }
    public function index()
    {
        $dormworkers=DB::table('users')->join('dorm_workers','users.id','=','dorm_workers.user_id')->join('dorms','dorms.id','=','dorm_workers.dorm_id')->select('users.name as userName','users.surname as userSurname','dorms.name as dormName','dorm_workers.id as dorm_worker_id','users.user_type as job')->orderBy('users.name')->get();
        //dd($dormworkers);
        return view('users.workers.index')->with('dormworkers',$dormworkers);
    }

    /**
     * Dodavanje novog radnika u domu.Da bi radnik bio dodat u dom,on vec mora imati radnicki nalog.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //$user_types=['admin', 'craftsman','clerical','laundryman','doorman'];
        $workers=DB::table('users')->select('id','name','surname')->whereIn("user_type",$this->user_types)->get();
        $dorms=DB::table('dorms')->select('id','name','address')->get();
        return view('users.workers.create')->with('data',['workers'=>$workers,'dorms'=>$dorms]);
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
            'user_id' => ['required','exists:users,id'],
            'dorm_id' => ['required','exists:dorms,id',Rule::unique('dorm_workers')
            ->where('user_id', $request->input('user_id'))],
        ],  
        [
            'dorm_id.unique' => 'Selected worker already works in that dorm!',
        ]);
            $dorm_worker=new DormWorker;
            $dorm_worker->user_id=$request->input('user_id');
            $dorm_worker->dorm_id=$request->input('dorm_id');
            $dorm_worker->save();
            return redirect()->route('dashboard')->with('success','New worker added to dorm.');
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
        //$user_types=['admin', 'craftsman','clerical','laundryman','doorman'];
        $workers=DB::table('users')->select('id','name')->whereIn("user_type",$this->user_types)->get();
        $dorms=DB::table('dorms')->select('id','name','address')->get();
        $dorm_worker=DormWorker::find($id);
        return view('users.workers.edit')->with('data',['workers'=>$workers,'dorms'=>$dorms,'id'=>$id,'dorm_worker'=>$dorm_worker]);
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
            'user_id' => ['required','exists:users,id'],
            'dorm_id' => ['required','exists:dorms,id',Rule::unique('dorm_workers')->ignore($id,'id')
            ->where('user_id', $request->input('user_id'))],
        ],  
        [
            'dorm_id.unique' => 'Selected worker already works in that dorm!',
        ]);
            $dorm_worker=DormWorker::find($id);
            $dorm_worker->user_id=$request->input('user_id');
            $dorm_worker->dorm_id=$request->input('dorm_id');
            $dorm_worker->save();
            return redirect()->route('workers.index')->with('success','Changes saved.');
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //dd($id);
        DormWorker::find($id)->delete();
        return redirect()->route('workers.index')->with('success','Worker removed from the dorm.');
    }
}
