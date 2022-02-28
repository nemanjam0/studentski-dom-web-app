<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ATM extends Model
{
    protected $table='atms';//zato sto je migracija kreirala a_t_m_s pa sam prepravio
}
