<?php

namespace App\Http\Controllers\Invoices;

use App\Http\Controllers\Controller;
use App\Dorm;
use App\Invoices\InvoiceTemplate;
use App\Invoices\DormInvoice;
use Illuminate\Http\Request;

class DormInvoiceController extends Controller
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
        //'dorm_invoices.dorm_id' 'dorm_invoices.invoice_template_id' kada dodas show rute ubaci da mogu direktno da kliknu za vise info.
        $dorminvoices=DormInvoice::join('invoice_templates','invoice_templates.id','=','dorm_invoices.invoice_template_id')->join('dorms','dorm_invoices.dorm_id','dorms.id')->select('dorm_invoices.id','dorm_invoices.students_finance_status','dorms.name as dorm_name','invoice_templates.name as template_name','invoice_templates.recurring','invoice_templates.days_to_pay','invoice_templates.amount_of_money')->get();
        return view('invoices.dorm.index')->with(['dorminvoices'=>$dorminvoices]);
        
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
        return view('invoices.dorm.create')->with(['dorms'=>$dorms,'templates'=>$templates]);
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
            'dorm'=>['integer','exists:dorms,id'],
            'template'=>['integer','exists:invoice_templates,id'],
            'students_finance_status'=>['in:budget,self-financing,all_types',function ($attribute, $value, $fail) use($request) {
             
                $status=$request->input('students_finance_status');
                $dorminvoices_status=DormInvoice::where('dorm_id',$request->input('dorm'))->where('invoice_template_id',$request->input('template'))->pluck('students_finance_status');
                $allowed=1;
               // dd($dorminvoices_status);
               $budget=0;$self_financing=0;
                foreach($dorminvoices_status as $dormstatus)
                {
                    if($dormstatus=='all_types' || $dormstatus==$status)
                    {
                        $allowed=0;
                    }
                    if($dormstatus=='budget')
                    {
                        $budget++;
                    }
                    else if($dormstatus=='self-financing')
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
                        $fail('Template is already set for budget and self-financing students in that dorm.You need to delete them.');
                    }
                    else if($budget>0)
                    {
                        $fail('Template is already set for budget students in that dorm.You need to delete it.');
                    }
                    else if($self_financing>0)
                    {
                        $fail('Template is already set for self-financing students in that dorm.You need to delete it.');     
                    }  
                    
                }
            }],

        ]);
        $dorminvoice=new DormInvoice;
        $dorminvoice->invoice_template_id=$request->input('template');
        $dorminvoice->dorm_id=$request->input('dorm');
        $dorminvoice->students_finance_status=$request->input('students_finance_status');
        $dorminvoice->save();
        return redirect('dashboard')->with('success','Dorm invoice created');
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
        $dormtemplate=DormInvoice::find($id);
        return view('invoices.dorm.edit')->with(['dorms'=>$dorms,'templates'=>$templates,'dormtemplate'=>$dormtemplate]);
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
            'dorm'=>['integer','exists:dorms,id'],
            'template'=>['integer','exists:invoice_templates,id'],
            'students_finance_status'=>['in:budget,self-financing,all_types',function ($attribute, $value, $fail) use($request,$id) {
             
                $status=$request->input('students_finance_status');
                $dorminvoices_status=DormInvoice::where('id','!=',$id)->where('dorm_id',$request->input('dorm'))->where('invoice_template_id',$request->input('template'))->pluck('students_finance_status');
                $allowed=1;
               // dd($dorminvoices_status);
               $budget=0;$self_financing=0;
                foreach($dorminvoices_status as $dormstatus)
                {
                    if($dormstatus=='all_types' || $dormstatus==$status)
                    {
                        $allowed=0;
                    }
                    if($dormstatus=='budget')
                    {
                        $budget++;
                    }
                    else if($dormstatus=='self-financing')
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
                        $fail('Template is already set for budget and self-financing students in that dorm.You need to delete them.');
                    }
                    else if($budget>0)
                    {
                        $fail('Template is already set for budget students in that dorm.You need to delete it.');
                    }
                    else if($self_financing>0)
                    {
                        $fail('Template is already set for self-financing students in that dorm.You need to delete it.');     
                    }    
                }
            }],
        ]);
        $dorminvoice=DormInvoice::find($id);
        $dorminvoice->invoice_template_id=$request->input('template');
        $dorminvoice->dorm_id=$request->input('dorm');
        $dorminvoice->students_finance_status=$request->input('students_finance_status');
        $dorminvoice->save();
        return redirect('invoices/dorm')->with('success','Dorm invoice edited');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DormInvoice::find($id)->delete();
        return redirect('invoices/dorm')->with('success','Dorm invoice deleted.');
    }
}
