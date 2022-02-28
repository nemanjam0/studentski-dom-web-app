<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Breakage extends Model
{
    public function reported_by()
    {
        return $this->belongsTo('App\User');
    }
    public function answered_by()
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
}
