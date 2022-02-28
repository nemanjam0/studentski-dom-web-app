<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Invoices\DormInvoice;
use App\Invoices\CategoryInvoice;
use App\Invoices\RoomInvoice;
use App\Invoices\InvoiceTemplate;
use App\Invoices\InvoiceLog;
use App\Room;
use App\Dorm;
use App\User;
use App\Student;
use Carbon\Carbon;
use DB;
use Config;

class SendInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendinvoices {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends invoices';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $date_arg=$this->argument('date');
       //$this->error("wrong date");
        $date=Carbon::now()->toDateTimeString();
        $date=Carbon::now(Config::get('app.timezone'));
      //  $log=new InvoiceLog();
       // $log->started=Carbon::tomorrow();
       // $log->successful=false;
       // $log->save();
      if($date_arg!=null)//ako smo prosleledili parametar komandi
      {
        try
        {
            $date_arg=Carbon::createFromFormat('d/m/Y', $date_arg); 
        }
        catch(\Exception $ex)
        {
            $this->error("Error wrong date");
            return 1;
        }
        $log=InvoiceLog::where(DB::raw('DATE(started)'),$date_arg->toDateString())->where('successful',1)->first();
        if($log!=null)
        {
            $this->error("Invoices for that day are already successfully executed");
            return 1;
        }
        else 
        {
            $date=$date_arg;//ako query vec nije ispravno izvrsen zapocinjemo sa izvrsenjem za taj dan
        }
      }
      else 
      {
        $log=InvoiceLog::where(DB::raw('DATE(started)'),$date->toDateString())->where('successful',1)->first();
        if($log!=null)
        {
            $this->error("Invoices for that day are already successfully executed");
            return 1;
        }
      }
       $log=new InvoiceLog();
       $log->started=$date;
       //dd($date);
       $log->successful=false;
       $saved=$log->save();//https://stackoverflow.com/questions/27877948/check-if-laravel-model-got-saved-or-query-got-executed
       if(!$saved)
       {
        $this->error("Error occured log was not created.Invoices are not sent.");
        return 1;
       }
      //  dd($date);
        //DB::enableQueryLog();
        $todays_template_ids=InvoiceTemplate::where(function ($query) {
            $query->where('recurring','monthly')
                  ->where('day',Carbon::now()->day);
        })->
        orWhere(function ($query) {
            $query->where('recurring','yearly')
                  ->where('day',Carbon::now()->day)->where('month',Carbon::now()->month);
        })->
        orWhere(function ($query) {
            $query->where('recurring','weekly')
                  ->where('day_of_the_week',Carbon::now()->dayOfWeek);
        })->orWhere('recurring','daily')->pluck('id');
        //DB::enableQueryLog();
        try
        {
            $ex=DB::transaction(function () use($todays_template_ids,$date,$log) {
            $dorminvoices=DB::table('invoice_templates')->whereIn('invoice_templates.id',$todays_template_ids)->join('dorm_invoices','dorm_invoices.invoice_template_id','=','invoice_templates.id')->join('dorms','dorms.id','=','dorm_invoices.dorm_id')->
            join('students', function ($join) {
                $join->on('students.dorm_id', '=', 'dorm_invoices.dorm_id')->whereRaw('IF(dorm_invoices.students_finance_status=?,TRUE,students.finance_status=dorm_invoices.students_finance_status)',['all_types']);
            })->join('users','users.id','=','students.user_id')->
            select('users.id as user_id','invoice_templates.id as template_id','invoice_templates.name as template_name','invoice_templates.amount_of_money',DB::raw('CURDATE() as date_of_issue'),DB::raw('DATE_ADD(CURDATE(),INTERVAL invoice_templates.days_to_pay DAY) as due_day'),DB::raw("'$date' as created_at"),DB::raw("'$date' as updated_at"));
          //  $y=DB::table('user_bills')->insertUsing(['user_id','invoice_template_id','name','amount_of_money','date_of_issue','due_date'],$x);
         // start
          $roominvoices=DB::table('invoice_templates')->whereIn('invoice_templates.id',$todays_template_ids)->join('room_invoices','room_invoices.invoice_template_id','=','invoice_templates.id')->
            join('students', function ($join){
             $join->on('students.room_id', '=', 'room_invoices.room_id')->whereRaw('IF(room_invoices.students_finance_status=?,TRUE,students.finance_status=room_invoices.students_finance_status)',['all_types']);
                 // $join->on('students.room_id', '=', 'room_invoices.room_id')->whereRaw('room_invoices.students_finance_status=?',['all_types'])->orWhere('students.finance_status','=','room_invoices.students_finance_status');                                                      
            })->join('users','users.id','=','students.user_id')->
            select('users.id as user_id','invoice_templates.id as template_id','invoice_templates.name as template_name','invoice_templates.amount_of_money',DB::raw('CURDATE() as date_of_issue'),DB::raw('DATE_ADD(CURDATE(),INTERVAL invoice_templates.days_to_pay DAY) as due_day'),DB::raw("'$date' as created_at"),DB::raw("'$date' as updated_at"));
           //end
            //start
            $categoryinvoices=DB::table('invoice_templates')->whereIn('invoice_templates.id',$todays_template_ids)->join('category_invoices','category_invoices.invoice_template_id','=','invoice_templates.id')->join('dorms','dorms.category','=','category_invoices.category')
            ->join('rooms',function ($join) {
                $join->on('rooms.dorm_id', '=', 'dorms.id')->whereNull('rooms.category');
                $join->orOn('rooms.category','=','category_invoices.category');
            })
            ->join('students', function ($join) {
                $join->on('students.room_id', '=', 'rooms.id')->whereRaw('IF(category_invoices.students_finance_status=?,TRUE,students.finance_status=category_invoices.students_finance_status)',['all_types']);
            })->join('users','users.id','=','students.user_id')->
            select('users.id as user_id','invoice_templates.id as template_id','invoice_templates.name as template_name','invoice_templates.amount_of_money',DB::raw('CURDATE() as date_of_issue'),DB::raw('DATE_ADD(CURDATE(),INTERVAL invoice_templates.days_to_pay DAY) as due_day'),DB::raw("'$date' as created_at"),DB::raw("'$date' as updated_at"));
            //end
           // dd($categoryinvoices->columns);
           // echo $x;
           $insert_dorm_invoices=DB::table('user_bills')->insertUsing(['user_id','invoice_template_id','name','amount_of_money','date_of_issue','due_date','created_at','updated_at'],$dorminvoices);
           $insert_room_invoices=DB::table('user_bills')->insertUsing(['user_id','invoice_template_id','name','amount_of_money','date_of_issue','due_date','created_at','updated_at'],$roominvoices);
           $insert_category_invoices=DB::table('user_bills')->insertUsing(['user_id','invoice_template_id','name','amount_of_money','date_of_issue','due_date','created_at','updated_at'],$categoryinvoices);
          // dd($insert_category_invoices);
           $log->successful=1;
           $log->save();
            });
        }
        catch(\Throwable $ex)//https://stackoverflow.com/questions/4790020/what-does-a-backslash-do-in-php-5-3 bez kose crte ne radi
        {
           $id=$log->id;
           $log=InvoiceLog::find($id);
            $log->query_log=$ex->getMessage();
            $log->save();
           
           // throw $ex;
            //dd($ex);
        }
        $this->info('Invoices are sent');
        return 0;
     //   dd($date);
     // //  dd($transaction);
           //echo Config::get('app.timezone');
          // $x=Carbon::now('Europe/Belgrade');
           //echo $x;
            //join->on('students.room_id', '=', 'room_invoices.room_id')->whereRaw('room_invoices.students_finance_status=?',['all_types'])->orWhere('students.finance_status','=','room_invoices.students_finance_status');
        //dd(DB::getQueryLog());
    }
}
