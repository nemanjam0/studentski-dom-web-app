<?php

namespace App\Invoices;

use Illuminate\Database\Eloquent\Model;

class InvoiceLog extends Model
{
    protected $dates = [
        'started',
    ];
}
