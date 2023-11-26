<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransmissionTypes extends Model
{

    // does not affects something but for the future
    protected $fillable = ['id', 'title', 'international_title'];
    protected  $table='transmission_types';
}
