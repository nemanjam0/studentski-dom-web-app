<?php

namespace App\Http\Controllers\Invoices;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Invoices\InvoiceLog;
use Carbon\Carbon;
use Artisan;

class InvoiceLogController extends Controller
{
    public function index()
    {
        $logs=InvoiceLog::orderByDesc('started')->paginate(20);
        return view('invoices.log.index')->with('logs',$logs);
        
    }
    public function retry(Request $request)
    {
        $log=InvoiceLog::find($request->input('id'));
        if($log==null)
        return redirect()->back()->with('error','Does not exist.');
        //dd();
        $exitCode=Artisan::call('sendinvoices',['date'=>$log->started->format('d/m/Y')]);
        if($exitCode==0)//uspesno
        {
            return redirect('invoices/log')->with('success',Artisan::output());
        }
        else
        {
            return redirect('invoices/log')->with('error',Artisan::output());
        }

    }
    public function new(Request $request)
    {
       
        $this->validate($request,['date'=>['required','date']]);
        $date=Carbon::createFromFormat('Y-m-d',$request->input('date'))->format('d/m/Y');
       // dd($request->input('date'));
        $exitCode=Artisan::call('sendinvoices',['date'=>$date]);
        if($exitCode==0)//uspesno
        {
            return redirect('invoices/log')->with('success',Artisan::output());
        }
        else
        {
            return redirect('invoices/log')->with('error',Artisan::output());
        }

    }
}
