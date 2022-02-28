<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\DormWorker;
use App\Dorm;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','birthday_date',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

  /*  public $user_types=[
        'admin','craftsman','student',
    ];*/
    public static function get_id_from_email($email)
    {
        $id=User::where('email',$email)->pluck('id')->first();
        return $id;
    }
    public function student()
    {
        return $this->hasOne('App\Student');//!!! mozda ne treba posto nije svaki korisnik student PROVERI!!!
    }
    public function bills()
    {
        return $this->hasMany('App\Invoices\UserBill')->orderBy('paid');
    }
    public function card()
    {
        return $this->hasOne('App\StudentCard','cardholder_id');
    }

    public function isAdmin()
    {
        if($this->user_type=='admin') return true;
        else return false;
    }

    public function isCraftsman()
    {
        if($this->user_type=='craftsman') return true;
        else return false;
    }

    public function isStudent()
    {
        if($this->user_type=='student') return true;
        else return false;
    }
    
    public function isClerical()
    {
        if($this->user_type=='clerical') return true;
        else return false;
    }
    public function isLaundryman()
    {
        if($this->user_type=='laundryman') return true;
        else return false;
    }
    public function isDoorman()
    {
        if($this->user_type=='doorman') return true;
        else return false;
    }
    public function worksInDorm($dormid)
    {
            $dorm=DormWorker::where('user_id','=',$this->id)->where('dorm_id','=',$dormid)->first();
            if($dorm!=null || $this->isAdmin()) return true;
            else return false;
    }
    public function workPlaces()
    {
   
            if($this->user_type=='admin')//admin radi u svakom domu(radi pristupa svim delovima sajta)
            {
                $dormids=Dorm::all()->pluck('id');
                return $dormids->toArray();
            }
            else
            $dormids=DormWorker::where('user_id','=',$this->id)->pluck('dorm_id');
            return $dormids->toArray();
    }
    public function reported_breakages()
    {
        return $this->hasMany('App\Breakage');
    }

    public function answered_breakages()
    {
        return $this->hasMany('App\Breakage');
    }
    public function borrowed_items()
    {
        return $this->hasMany('App\BorrowedItem');
    }
    public function work_places()
    {
        return $this->belongsToMany('App\DormWorker');
    }
}
