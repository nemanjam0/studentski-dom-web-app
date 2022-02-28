<?php

namespace App\Http\Controllers\Invoices;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Invoices\InvoiceTemplate;
use Illuminate\Validation\Rule;

class TemplateController extends Controller
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
        $templates=InvoiceTemplate::all();
        return view('invoices.template.index')->with('templates',$templates);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('invoices.template.create');
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
            'name' => ['required', 'string', 'max:20','unique:invoice_templates'],
            'recurring'=>['in:no,template,daily,weekly,monthly,yearly'],
            'amount_of_money' => ['required', 'integer', 'min:1'],
            'days_to_pay' => ['required', 'integer', 'min:1'],
            'day' => ['required_if:recurring,monthly','required_if:recurring,yearly'],
            'month' => ['required_if:recurring,yearly'],
            'day_of_the_week' => ['required_if:recurring,weekly'],
            ]);
            $template=new InvoiceTemplate;
            if($request->input('recurring')=='monthly')//mora ovako zato sto se ubaguje ako stavim gore tj. ako recurring nije monthly idalje trazi broj i idalje trazi da broj bude u opsegu
            {
                $this->validate($request,[
                    'day' => ['integer','min:1','max:28'],
                    ]);
                    $template->day=$request->input('day');
            }
            if($request->input('recurring')=='yearly')
            {
                $this->validate($request,[
                    'day' => ['integer','min:1','max:28'],
                    'month' => ['integer','min:1','max:12'],
                    ]);
                    $template->day=$request->input('day');
                    $template->month=$request->input('month');
            }
            if($request->input('recurring')=='weekly')
            {
                $this->validate($request,[
                    'day_of_the_week' => ['integer','min:1','max:7'],
                    ]);
                    $template->day_of_the_week=$request->input('day_of_the_week');
            }
            $template->name=$request->input('name');
            $template->recurring=$request->input('recurring');
            $template->amount_of_money=$request->input('amount_of_money');
            $template->days_to_pay=$request->input('days_to_pay');
            $template->save();
            return redirect('dashboard')->with('success','Invoice template created');
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
        $template=InvoiceTemplate::find($id);
        return view('invoices.template.edit')->with('template',$template);
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
            'name' => ['required', 'string', 'max:20',Rule::unique('invoice_templates')->ignore($id, 'id')],
            'recurring'=>['in:no,template,daily,weekly,monthly,yearly'],
            'amount_of_money' => ['required', 'integer', 'min:1'],
            'days_to_pay' => ['required', 'integer', 'min:1'],
            'day' => ['required_if:recurring,monthly','required_if:recurring,yearly'],
            'month' => ['required_if:recurring,yearly'],
            'day_of_the_week' => ['required_if:recurring,weekly'],
            ]);
        $template=InvoiceTemplate::find($id);
        if($request->input('recurring')=='monthly')//mora ovako zato sto se ubaguje ako stavim gore tj. ako recurring nije monthly idalje trazi broj i idalje trazi da broj bude u opsegu
        {
            $this->validate($request,[
                'day' => ['integer','min:1','max:28'],
                ]);
                $template->day=$request->input('day');
        }
        if($request->input('recurring')=='yearly')
        {
            $this->validate($request,[
                'day' => ['integer','min:1','max:28'],
                'month' => ['integer','min:1','max:12'],
                ]);
                $template->day=$request->input('day');
                $template->month=$request->input('month');
        }
        if($request->input('recurring')=='weekly')
        {
            $this->validate($request,[
                'day_of_the_week' => ['integer','min:1','max:7'],
                ]);
                $template->day_of_the_week=$request->input('day_of_the_week');
        }
        $template->name=$request->input('name');
        $template->recurring=$request->input('recurring');
        $template->amount_of_money=$request->input('amount_of_money');
        $template->days_to_pay=$request->input('days_to_pay');
        $template->save();
        return redirect('invoices/template')->with('success','Invoice template edited');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        InvoiceTemplate::find($id)->delete();
        return redirect('invoices/template')->with('success','Invoice template deleted.');

    }
}
