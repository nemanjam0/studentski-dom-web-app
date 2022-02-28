<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use Illuminate\Validation\Rule;

class ItemsController extends Controller
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
        $items=Item::all();
        return view('items.index')->with('items',$items);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('items.create');
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
            'name' => ['required', 'string', 'max:30','unique:items,name'],
            'description' => ['nullable', 'string', 'max:150'],
            ]);
            $item=new Item;
            $item->name=$request->input('name');
            $item->description=$request->input('description');
            $item->save();
            return redirect()->route('dashboard')->with('success','New item created');
         
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item=Item::find($id);
        return view('items.edit')->with('item',$item);
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
            'name' => ['required', 'string', 'max:30',Rule::unique('items')->ignore($id, 'id')],
            'description' => ['nullable', 'string', 'max:150'],
            ]);
            $item=Item::find($id);
            $item->name=$request->input('name');
            $item->description=$request->input('description');
            $item->save();
            return redirect()->route('items.index')->with('success','Changes saved');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Item::find($id)->delete();
        return redirect('items')->with('success','Item deleted');
    }
}
