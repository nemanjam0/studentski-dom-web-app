<?php

namespace App\Http\Controllers\Invoices;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Invoices\InvoiceTemplate;
use App\Invoices\RoomInvoice;
use App\Dorm;
use App\Room;

class RoomInvoiceController extends Controller
{
    public function __construct()
    {
        
        $this->middleware(['CheckRole:admin']);//samo admin moze da pristupe ovom delu 
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //'room_invoices.room_id' 'room_invoices.invoice_template_id' kada dodas show rute ubaci da mogu direktno da kliknu za vise info.
        $roominvoices=RoomInvoice::join('invoice_templates','invoice_templates.id','=','room_invoices.invoice_template_id')->join('rooms','room_invoices.room_id','rooms.id')->join('dorms','rooms.dorm_id','dorms.id')->select('room_invoices.id','room_invoices.students_finance_status','dorms.name as dorm_name','rooms.room_number as room_number','invoice_templates.name as template_name','invoice_templates.recurring','invoice_templates.days_to_pay','invoice_templates.amount_of_money')->get();
        return view('invoices.room.index')->with(['roominvoices'=>$roominvoices]);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dorms=Dorm::all();
        $templates=InvoiceTemplate::all();
        return view('invoices.room.create')->with(['dorms'=>$dorms,'templates'=>$templates]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $roomid=null;
        $this->validate($request,[
            'dorm'=>['integer','exists:dorms,id'],
            'room_number'=>['required','integer','min:0',function ($attribute, $value, $fail) use($request,&$roomid) {
                $roomid=Room::where('dorm_id',$request->input('dorm'))->where('room_number',$request->input('room_number'))->pluck('id')->first();
                if($roomid==null)
                $fail('Room with that number does not exist in that dorm .');
            }],
            'template'=>['integer','exists:invoice_templates,id'],
            'students_finance_status'=>['in:budget,self-financing,all_types',function ($attribute, $value, $fail) use($request,&$roomid) {
             
                $status=$request->input('students_finance_status');
                $roominvoices_status=RoomInvoice::where('room_id',$roomid)->where('invoice_template_id',$request->input('template'))->pluck('students_finance_status');
                $allowed=1;
               // dd($roominvoices_status);
               $budget=0;$self_financing=0;
                foreach($roominvoices_status as $roomstatus)
                {
                    if($roomstatus=='all_types' || $roomstatus==$status)
                    {
                        $allowed=0;
                    }
                    if($roomstatus=='budget')
                    {
                        $budget++;
                    }
                    else if($roomstatus=='self-financing')
                    {
                        $self_financing++;
                    }
                }
                if($allowed==0)
                {
                    $fail('Already exists.');
                }
                else if($status=='all_types')//ako vec postoji isti template za taj dom,a mi pokusamo da dodamo da dodamo all_types to nije dozvoljeno(ako postoji za budzetske,a mi dodamo all_types budzetskim ce 2x stizati racun)
                {
                    if($budget>0 && $self_financing>0)
                    {
                        $fail('Template is already set for budget and self-financing students in that room.You need to delete them.');
                    }
                    else if($budget>0)
                    {
                        $fail('Template is already set for budget students in that room.You need to delete it.');
                    }
                    else if($self_financing>0)
                    {
                        $fail('Template is already set for self-financing students in that room.You need to delete it.');     
                    }  
                    
                }
            }],

        ]);
        $roominvoice=new RoomInvoice;
        $roominvoice->invoice_template_id=$request->input('template');
        $roominvoice->room_id=$roomid;
        $roominvoice->students_finance_status=$request->input('students_finance_status');
        $roominvoice->save();
        return redirect('dashboard')->with('success','Room invoice created');
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
        $dorms=Dorm::all();
        $templates=InvoiceTemplate::all();
        $roomtemplate=RoomInvoice::where('room_invoices.id','=',$id)->join('rooms','rooms.id','=','room_invoices.room_id')->select('room_invoices.*','rooms.room_number')->first();
        //dd($roomtemplate);
        return view('invoices.room.edit')->with(['dorms'=>$dorms,'templates'=>$templates,'roomtemplate'=>$roomtemplate]);
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
        $roomid=null;
        $this->validate($request,[
            'dorm'=>['integer','exists:dorms,id'],
            'room_number'=>['required','integer','min:0',function ($attribute, $value, $fail) use($request,&$roomid,$id) {
                $roomid=Room::where('dorm_id',$request->input('dorm'))->where('room_number',$request->input('room_number'))->pluck('id')->first();
                if($roomid==null)
                $fail('Room with that number does not exist in that dorm .');
            }],
            'template'=>['integer','exists:invoice_templates,id'],
            'students_finance_status'=>['in:budget,self-financing,all_types',function ($attribute, $value, $fail) use($request,&$roomid,$id) {
             
                $status=$request->input('students_finance_status');
                $roominvoices_status=RoomInvoice::where('id','!=',$id)->where('room_id',$roomid)->where('invoice_template_id',$request->input('template'))->pluck('students_finance_status');
                $allowed=1;
               // dd($roominvoices_status);
               $budget=0;$self_financing=0;
                foreach($roominvoices_status as $roomstatus)
                {
                    if($roomstatus=='all_types' || $roomstatus==$status)
                    {
                        $allowed=0;
                    }
                    if($roomstatus=='budget')
                    {
                        $budget++;
                    }
                    else if($roomstatus=='self-financing')
                    {
                        $self_financing++;
                    }
                }
                if($allowed==0)
                {
                    $fail('Already exists.');
                }
                else if($status=='all_types')//ako vec postoji isti template za taj dom,a mi pokusamo da dodamo da dodamo all_types to nije dozvoljeno(ako postoji za budzetske,a mi dodamo all_types budzetskim ce 2x stizati racun)
                {
                    if($budget>0 && $self_financing>0)
                    {
                        $fail('Template is already set for budget and self-financing students in that room.You need to delete them.');
                    }
                    else if($budget>0)
                    {
                        $fail('Template is already set for budget students in that room.You need to delete it.');
                    }
                    else if($self_financing>0)
                    {
                        $fail('Template is already set for self-financing students in that room.You need to delete it.');     
                    }  
                    
                }
            }],

        ]);
        $roominvoice=RoomInvoice::find($id);
        $roominvoice->invoice_template_id=$request->input('template');
        $roominvoice->room_id=$roomid;
        $roominvoice->students_finance_status=$request->input('students_finance_status');
        //dd($roominvoice);
        $roominvoice->save();
        return redirect('invoices/room')->with('success','Room invoice edited');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        RoomInvoice::find($id)->delete();
        return redirect('invoices/room')->with('success','Room invoice deleted.');
    }
}
