<?php

namespace App;

use App\Traits\ModelBelongsToVehicleTrait;
use App\Traits\ModelCreatesActionLogsTrait;
use App\Traits\ModelHasDocumentsTrait;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
/**
 * @property int         $id
 * @property string      $created_at
 * @property string      $updated_at
 * @property string      $deleted_at
 * @property int         $periodic_fee_type_id
 * @property int         $vehicle_id
 * @property string      $title
 * @property string|null $description
 * @property float       $fee
 * @property string|null $date_start
 * @property string|null $date_expiration
 * @property string|null $date_payed
 */
class PeriodicFee extends Model
{
    use ModelBelongsToVehicleTrait;
    use ModelHasDocumentsTrait;
    use ModelCreatesActionLogsTrait;

    protected $fillable = ['id'];

    protected $casts = [
        'fee' => 'float',
    ];

    public function getDocumentsPath()
    {
        'cars/car-'.$this->vehicle_id.'/periodic-fees/';
    }

    public function getInitialFileName()
    {
        return 'periodic-fee';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fee_type()
    {
        return $this->belongsTo(PeriodicFeeType::class, 'periodic_fee_type_id', 'id');
    }

    public function getDateExpirationAttribute() {
        return Carbon::parse($this->attributes['date_expiration'])->format('Y-m-d');
    }
}