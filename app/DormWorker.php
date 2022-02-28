<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DormWorker extends Model
{
    protected $table='dorm_workers';

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function dorm()
    {
        return $this->belongsTo('App\Dorm');
    }
}
