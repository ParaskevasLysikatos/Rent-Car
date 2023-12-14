<?php

namespace App\Models;

use App\Traits\ModelBelongsToVehicleTrait;
use App\Traits\ModelHasDocumentsTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property int    $vehicle_id
 * @property string $licence_plate
 * @property string $registration_date
 */
class LicencePlate extends Model
{
    use ModelBelongsToVehicleTrait;
    use ModelHasDocumentsTrait;

    protected $fillable = [
        'id', 'vehicle_id', 'licence_plate', 'registration_date'
    ];

    public function getDocumentsPath()
    {
        'cars/car-'.$this->vehicle_id.'/licence-plate/';
    }

    public function getInitialFileName()
    {
        return 'licence-plate';
    }
}
