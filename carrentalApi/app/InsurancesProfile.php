<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InsurancesProfile extends Model
{
    protected $fillable = [
        'insurance_id', 'language_id',
    ];
}
