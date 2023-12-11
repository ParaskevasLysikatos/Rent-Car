<?php

namespace App\Models;

use App\Filters\VisitFilter;
use App\Traits\ModelAffectsVehicleTrait;
use App\Traits\ModelCreatesActionLogsTrait;
use App\Traits\ModelHasCommentsTrait;
use App\Traits\ModelIsSortableTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int    $id
 * @property string $date_start
 * @property int    $vehicle_id
 * @property int    $km
 */
class ServiceVisit extends Model
{
    use ModelAffectsVehicleTrait;
    use ModelCreatesActionLogsTrait;
    use ModelHasCommentsTrait;
    use ModelIsSortableTrait;
    use SoftDeletes;

    protected $table = 'service_visit';

    protected $fillable = [
        'id',
    ];

    public function scopeFilter(Builder $builder, $request) // v2 filters, see filters folder
    {
        return (new VisitFilter($request))->filter($builder);
    }


    public function visit_details()
    {
        return $this->hasMany(ServiceVisitDetails::class, 'service_visit_id', 'id');
    }

}
