<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RateCodesProfile extends Model
{
    protected $fillable = [
        'rate_code_id', 'language_id',
    ];
}
