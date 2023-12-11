<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DriveTypes extends Model
{
    // does not affects something but for the future
    protected $fillable = ['id', 'title', 'short_description'];

    protected $table = 'drive_types';
}