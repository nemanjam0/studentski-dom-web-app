<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\College;
use DB;
use Illuminate\Validation\Rule;
class CollegeController extends Controller
{
    public function __construct()
    {
        
        $this->middleware(['CheckRole:admin']);//samo admin moze da pristupe ovom delu 
    }
    public function index()
    {
        $colleges=DB::table('colleges')->get();
        return view('colleges.index')->with('colleges',$colleges);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('colleges.create');
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
            'name' => ['required', 'string', 'max:100','unique:colleges'],
            'address' => ['required', 'string', 'max:100'],
            ]);
         
            $college=new College;
            $college->name=$request->input('name');
            $college->address=$request->input('address');
            $college->save();
            return redirect('dashboard')->with('success','College created');
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
        $college=College::find($id);
        return view('colleges.edit')->with('college',$college);
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
            'name' => ['required', 'string', 'max:100',Rule::unique('colleges')->ignore($id, 'id')],
            'address' => ['required', 'string', 'max:100'],
            ]);
            $college=College::find($id);
            $college->name=$request->input('name');
            $college->address=$request->input('address');
            $college->save();
            return redirect()->route('colleges.index')->with('success','Changes saved');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        College::find($id)->delete();
        return redirect()->route('colleges.index')->with('success','College deleted');
    }
}
