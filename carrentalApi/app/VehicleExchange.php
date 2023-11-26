<?php

namespace App;

use App\Filters\VehicleExchangeFilter;
use App\Traits\ModelIsSortableTrait;
use App\Traits\ModelHasDocumentsTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class VehicleExchange extends Model
{
    use ModelIsSortableTrait;
    use ModelHasDocumentsTrait;

    protected $fillable = ['id'];

    const TYPE_OFFICE = 'office';
    const TYPE_OUTSIDE = 'outside';

    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';


    public function scopeFilter(Builder $builder, $request) // v2 filters, see filters folder
    {
        return (new VehicleExchangeFilter($request))->filter($builder);
    }

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }

    public function old_vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function old_vehicle_transition()
    {
        return $this->belongsTo(Transition::class);
    }

    public function new_vehicle_type()
    {
        return $this->belongsTo(Type::class);
    }

    public function new_vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function new_vehicle_transition()
    {
        return $this->belongsTo(Transition::class);
    }

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    public function getOldVehicleKmAttribute()
    {
        return $this->old_vehicle_rental_ci_km - $this->old_vehicle_rental_co_km;
    }

    public function getNewVehicleKmAttribute()
    {
        $km = $this->new_vehicle_transition ? $this->new_vehicle_transition->ci_km : 0;
        if ($km > 0) {
            $km -= $this->new_vehicle_transition->co_km;
        }
        return  $km;
    }


    public function getDocumentsPath()
    {
        return 'exchanges/exchange-' . $this->id . '/documents/';
    }


    public function getInitialFileName()
    {
        return 'exchangeID';
    }
}
