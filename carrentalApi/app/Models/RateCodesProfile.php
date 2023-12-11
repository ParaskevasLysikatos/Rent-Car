<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RateCodesProfile extends Model
{
    protected $fillable = [
        'rate_code_id', 'language_id',
    ];
}
