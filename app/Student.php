<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable=['user_id','fakultet_id','godina_studiranja','indeks','status_finansiranja','stanar_doma','dorm_id','room_id'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function dorm()
    {
        return $this->belongsTo('App\Dorm');
    }
    public function room()
    {
        return $this->belongsTo('App\Room');
    }
    public function college()
    {
        return $this->belongsTo('App\College','college_id')->withDefault();
    }
    public function isDormTenant()
    {
        if($this->dorm_id!=null && $this->room_id!=null && $this->dorm_tenant!=0) return true;
        else return false;
    }

    
}
