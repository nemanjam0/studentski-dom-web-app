<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dorm extends Model
{
    protected $fillabe=['name','address'];
    public function rooms()
    {
        return $this->hasMany('App\Room');
    }
    public function workers()
    {
        return $this->belongsTo('App\DormWorker');
    }
    public function user()
    {
        return $this->belongsToMany('App\User');
    }


}
