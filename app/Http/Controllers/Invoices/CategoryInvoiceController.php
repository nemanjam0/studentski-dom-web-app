<?php

namespace App\Http\Controllers\Invoices;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Invoices\InvoiceTemplate;
use App\Invoices\CategoryInvoice;

class CategoryInvoiceController extends Controller
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
        $categoryinvoices=CategoryInvoice::join('invoice_templates','invoice_templates.id','=','category_invoices.invoice_template_id')->select('category_invoices.id','category_invoices.category','category_invoices.students_finance_status','invoice_templates.name as template_name','invoice_templates.recurring','invoice_templates.days_to_pay','invoice_templates.amount_of_money')->get();
        return view('invoices.category.index')->with(['categoryinvoices'=>$categoryinvoices]);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $templates=InvoiceTemplate::all();
        return view('invoices.category.create')->with(['templates'=>$templates]);
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
            'category'=>['integer','required','min:1'],
            'template'=>['integer','exists:invoice_templates,id'],
            'students_finance_status'=>['in:budget,self-financing,all_types',function ($attribute, $value, $fail) use($request) {
             
                $status=$request->input('students_finance_status');
                $categoryinvoices_status=CategoryInvoice::where('category',$request->input('category'))->where('invoice_template_id',$request->input('template'))->pluck('students_finance_status');
                $allowed=1;

               // dd($categoryinvoices_status);
               $budget=0;$self_financing=0;
                foreach($categoryinvoices_status as $cateogrystatus)
                {
                    if($cateogrystatus=='all_types' || $cateogrystatus==$status)
                    {
                        $allowed=0;
                    }
                    if($cateogrystatus=='budget')
                    {
                        $budget++;
                    }
                    else if($cateogrystatus=='self-financing')
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
                        $fail('Template is already set for budget and self-financing students in that category.You need to delete them.');
                    }
                    else if($budget>0)
                    {
                        $fail('Template is already set for budget students in that category.You need to delete it.');
                    }
                    else if($self_financing>0)
                    {
                        $fail('Template is already set for self-financing students in that category.You need to delete it.');     
                    }  
                    
                }
            }],

        ]);
        $categoryinvoice=new CategoryInvoice;
        $categoryinvoice->invoice_template_id=$request->input('template');
        $categoryinvoice->category=$request->input('category');
        $categoryinvoice->students_finance_status=$request->input('students_finance_status');
        $categoryinvoice->save();
        return redirect('dashboard')->with('success','Category invoice created');
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
        $templates=InvoiceTemplate::all();
        $categoryinvoice=CategoryInvoice::find($id);
        //dd($categoryinvoice);
        return view('invoices.category.edit')->with(['templates'=>$templates,'categoryinvoice'=>$categoryinvoice]);
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
            'category'=>['integer','required','min:1'],
            'template'=>['integer','exists:invoice_templates,id'],
            'students_finance_status'=>['in:budget,self-financing,all_types',function ($attribute, $value, $fail) use($request,$id) {
             
                $status=$request->input('students_finance_status');
                $categoryinvoices_status=CategoryInvoice::where('category',$request->input('category'))->where('category_invoices.id','!=',$id)->where('invoice_template_id',$request->input('template'))->pluck('students_finance_status');
                $allowed=1;
               // dd($categoryinvoices_status);
               $budget=0;$self_financing=0;
                foreach($categoryinvoices_status as $categorystatus)
                {
                    if($categorystatus=='all_types' || $categorystatus==$status)
                    {
                        $allowed=0;
                    }
                    if($categorystatus=='budget')
                    {
                        $budget++;
                    }
                    else if($categorystatus=='self-financing')
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
                        $fail('Template is already set for budget and self-financing students in that category.You need to delete them.');
                    }
                    else if($budget>0)
                    {
                        $fail('Template is already set for budget students in that category.You need to delete it.');
                    }
                    else if($self_financing>0)
                    {
                        $fail('Template is already set for self-financing students in that category.You need to delete it.');     
                    }  
                    
                }
            }],

        ]);
        $categoryinvoice=CategoryInvoice::find($id);
        $categoryinvoice->invoice_template_id=$request->input('template');
        $categoryinvoice->category=$request->input('category');
        $categoryinvoice->students_finance_status=$request->input('students_finance_status');
        $categoryinvoice->save();
        return redirect('invoices/category')->with('success','Category invoice edited');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        CategoryInvoice::find($id)->delete();
        return redirect('invoices/category')->with('success','Category invoice deleted.');
    }
}
