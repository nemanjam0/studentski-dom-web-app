<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;//za datume
use DateTime;

class OvernightStay extends Model
{
    protected $table='overnight_stay';
    protected $dates=['check_in','check_out'];

    /**
     * Gets array of month in which user had exceed number of overnight stays
     *
     * @param  \Carbon\Cabon $periodstarts The beggining of the period in which we are searching over exceeded overnight stays
     * @param  \Carbon\Cabon $periodends The end of the period in which we are searching over exceeded overnight stays
     * @param int $overnightlimit how many nights per month user have right to have guest in room
     * @return Array with names of months in which user had exceed the overnight stays
     */
    public static function exceedovernights($hostid,Carbon $periodstarts,Carbon $periodends,$overnightlimit)
    {
        //dodaj i period koji ubacujes kako bi se sve proverilo
        $newovernight=new OvernightStay;//iz baze povlacimo vec uzeta prenocista ali moramo uracunati i novo prenociste koje korisnik zeli da uzme prilikom brojanja koliko nocenja je korisnik uzeo u toku odredjenog meseca kako bi sprecili prekoracenje
        $newovernight->check_in=$periodstarts;
        $newovernight->check_out=$periodends;
        $periodstarts->day=1;
        $periodends->day=$periodends->daysInMonth;
        $overnights=OvernightStay::where('host','=',$hostid)->where('check_in','<=',$periodends->toDateString())->where('check_out','>=',$periodstarts->toDateString())->select('check_in','check_out')->get();
        $overnights->push($newovernight);
        //dd($overnights);
        $overnightsinmonth=[];//inicijalizacija
        foreach($overnights as $night)
        {    
            if($night->check_in->year == $night->check_out->year)//za slucaj da je sve u jednoj godini
            {
              
                for($i=$night->check_in->month;$i<=$night->check_out->month;$i++)
                {
                
                    $key=$night->check_out->year.'.'.$i;//kako bi se smestao broj prenocista u array za taj mesec
                 
                    if($night->check_out->month==$i)//za racunanje koliko prenocista ima u datom mesecu
                    $monthend=$night->check_out->day;
                    else
                    $monthend=cal_days_in_month(CAL_GREGORIAN, $i, $night->check_in->year);
                    if($night->check_in->month==$i)
                    $monthstart=$night->check_in->day;
                    else
                    $monthstart=1;
                    if (!array_key_exists($key, $overnightsinmonth))
                    $overnightsinmonth[$key]=($monthend-$monthstart)+1;
                    else
                    $overnightsinmonth[$key]+=($monthend-$monthstart)+1;
                    //dd($overnightsinmonth[$key]);
                }
            }
            else
            {
                for($i=$night->check_in->month;$i<=12;$i++)//do kraja check in godine
                {
                
                    $key=$night->check_out->year.'.'.$i;//kako bi se smestao broj prenocista u array za taj mesec
                    $monthend=cal_days_in_month(CAL_GREGORIAN, $i, $night->check_in->year);
                    if($night->check_in->month==$i)
                    $monthstart=$night->check_in->day;
                    else
                    $monthstart=1;
                    if(!array_key_exists($key, $overnightsinmonth))
                    $overnightsinmonth[$key]=($monthend-$monthstart)+1;
                    else
                    $overnightsinmonth[$key]+=($monthend-$monthstart)+1;
                }
                for($i=1;$i<=$night->check_out->month;$i++)//od pocetka check_out godine
                {
                
                    $key=$night->check_out->year.'.'.$i;//kako bi se smestao broj prenocista u array za taj mesec
                    if($night->check_out->month==$i)//za racunanje koliko prenocista ima u datom mesecu
                    $monthend=$night->check_out->day;
                    else
                    $monthend=cal_days_in_month(CAL_GREGORIAN, $i, $night->check_out->year);// u slucaju da to nije zadnji mesec u peridou uzima se zadnji dan u mesecu kao kraj meseca
                    $monthstart=1;
                    if(!array_key_exists($key, $overnightsinmonth))
                    $overnightsinmonth[$key]=($monthend-$monthstart)+1;
                    else
                    $overnightsinmonth[$key]+=($monthend-$monthstart)+1;
                }
            }    
        }
        $exceeded='';
        $periodstartskey=$periodstarts->year.'.'.$periodstarts->month;
        $periodendskey=$periodends->year.'.'.$periodends->month;
      //  dd($periodendskey);
        foreach($overnightsinmonth as $key=>$value)
        {
            
            if($value>$overnightlimit && ($key>=$periodstartskey && $key<=$periodendskey))
            {
                $monthnum=explode('.',$key);
                $monthnum=end($monthnum);
                $monthName   = DateTime::createFromFormat('!m', $monthnum);
      
                $monthName = $monthName->format('F');
                if($exceeded!='')// kako bi se izbeglo npr ,March
                $exceeded.=','.$monthName;
                else
                $exceeded.=$monthName;
            }
        }
       return $exceeded;
    }
}
