<?php

namespace App;

use App\Traits\ModelIsLocalizedProfileTrait;
use Illuminate\Database\Eloquent\Model;
use App\Place;
class PlaceProfile extends Model
{
    use ModelIsLocalizedProfileTrait;

    protected $fillable = [
        'place_id', 'language_id',
    ];

    public function place()
    {
        return $this->belongsTo(Place::class, 'place_id', 'id');
    }
}
