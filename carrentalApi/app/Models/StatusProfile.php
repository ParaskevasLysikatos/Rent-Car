<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusProfile extends Model
{
    protected $table = 'vehicle_status_profile';
    protected $fillable = ['vehicle_status_id'];
}
