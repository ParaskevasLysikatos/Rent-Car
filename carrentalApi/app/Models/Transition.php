<?php

namespace App\Models;

use App\Filters\TransitionFilter;
use App\Traits\ModelAffectsVehicleStatusInterface;
use App\Traits\ModelAffectsVehicleStatusTrait;
use App\Traits\ModelAffectsVehicleTrait;
use App\Traits\ModelBelongsToUserTrait;
use App\Traits\ModelCreatesActionLogsTrait;
use App\Traits\ModelHasDocumentsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use App\TransitionType;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int         $id
 * @property string      $created_at
 * @property string      $updated_at
 * @property string|null $completed_at
 * @property string      $deleted_at
 * @property int         $user_id
 * @property int         $vehicle_id
 * @property string      $type
 * @property int         $station_id_from
 * @property int         $station_id_to
 */
class Transition extends Model implements ModelAffectsVehicleStatusInterface
{
    use ModelAffectsVehicleTrait;
    use ModelAffectsVehicleStatusTrait;
    use ModelBelongsToUserTrait;
    use ModelCreatesActionLogsTrait;
    use ModelHasDocumentsTrait;
    use SoftDeletes;
    use Notifiable;

    protected $fillable = [
        'id',
    ];

    public function scopeFilter(Builder $builder, $request) // v2 filters, see filters folder
    {
        return (new TransitionFilter($request))->filter($builder);
    }


    public function getDocumentsPath()
    {
        return 'cars/car-'.$this->vehicle_id.'/transitions/';
    }

    public function getInitialFileName()
    {
        return 'carID-'.$this->vehicle_id;
    }

    public function type()
    {
        return $this->belongsTo(TransitionType::class, 'type_id', 'id');
    }

    public function checkedIn(): bool {
        return (!is_null($this->ci_km) && !is_null($this->ci_fuel_level));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
          return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function co_user()
    {
        return $this->belongsTo(User::class, 'co_user_id', 'id');
    }

    public function ci_user()
    {
        return $this->belongsTo(User::class, 'ci_user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function co_station()
    {
        return $this->belongsTo(Station::class, 'co_station_id', 'id');
    }
    public function ci_station()
    {
        return $this->belongsTo(Station::class, 'ci_station_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function co_place()
    {
        return $this->belongsTo(Place::class, 'co_place_id', 'id');
    }
    public function ci_place()
    {
        return $this->belongsTo(Place::class, 'ci_place_id', 'id');
    }
}
