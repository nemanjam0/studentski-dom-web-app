<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoanedItem extends Model
{
    protected $fillable = [
        'borrower_id', 'borrowed_by_id', 'item_id','quantity',
    ];
    public function borrower()
    {
        return $this->hasOne('App\User');
    }
    public function borrowed_by()
    {
        return $this->hasOne('App\User');
    }
    public function item_type()
    {
        return $this->hasOne('App\Item');
    }
}
