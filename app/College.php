<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class College extends Model
{
    protected $fillabe =['name','address'];
    protected $attributes = [
        'name' => 'Unknown',
        'address'=>'Unknown',
    ];

    public function student()
    {
        return $this->belongsToMany('App\Student');
    }
}
