<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Student;

class Room extends Model
{
    protected $fillabe=['dorm_id','room_number','room_capacity','floor'];
    public function dorm()
    {
        return $this->belongsTo('App\Dorm');
    }
    public function user()
    {
        return $this->belongsToMany('App\User');
    }
}
