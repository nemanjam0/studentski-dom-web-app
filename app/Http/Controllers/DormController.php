<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Dorm;
use DB;
use Illuminate\Validation\Rule;
class DormController extends Controller
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
        $dorms=DB::table('dorms')->get();
        return view('dorms.index')->with('dorms',$dorms);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dorms.create');
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
            'name' => ['required', 'string', 'max:100','unique:dorms'],
            'address' => ['required', 'string', 'max:100','unique:dorms'],
            'category' =>['required','min:0','integer'],
            ]);
         
            $dorm=new Dorm;
            $dorm->name=$request->input('name');
            $dorm->address=$request->input('address');
            $dorm->category=$request->input('category');
            $dorm->save();
            return redirect('dashboard')->with('success','Dorm created');
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
        $dorm=Dorm::find($id);
        return view('dorms.edit')->with('dorm',$dorm);
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
            'name' => ['required', 'string', 'max:100',Rule::unique('dorms')->ignore($id, 'id')],
            'address' => ['required', 'string', 'max:100'],
            'category' =>['required','min:0','integer'],
            ]);
            $dorm=Dorm::find($id);
            $dorm->name=$request->input('name');
            $dorm->address=$request->input('address');
            $dorm->category=$request->input('category');
            $dorm->save();
            return redirect('dorms')->with('success','Changes saved');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Dorm::find($id)->delete();
        return redirect('dorms')->with('success','Dorm deleted');
    }
}
