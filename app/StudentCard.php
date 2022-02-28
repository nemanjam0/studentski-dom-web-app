<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentCard extends Model
{
    protected $dates = [
        'renewed_at',
    ];
    public function cardholder()
    {
        return $this->belongsTo('App\User');
    }
    public function cardtype()
    {
        return $this->belongsTo('App\Card');
    }
}
