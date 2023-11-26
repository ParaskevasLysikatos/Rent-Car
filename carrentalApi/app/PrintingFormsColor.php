<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrintingFormsColor extends Model
{
    protected $fillable = [
        'brand_id',
        'print_form'
    ];
    public $timestamps = false;
}
