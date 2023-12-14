<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyPreferences extends Model
{
    protected $table = 'company_preferences';
    public $fillable = ['id'];

    public function station() {
        return $this->belongsTo(Station::class);
    }

    public function place() {
        return $this->belongsTo(Place::class);
    }

    public function quote_source() {
        return $this->belongsTo(BookingSource::class);
    }

    public function booking_source() {
        return $this->belongsTo(BookingSource::class);
    }

    public function rental_source() {
        return $this->belongsTo(BookingSource::class);
    }
}
