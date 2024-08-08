<?php

namespace  App\Models;

use App\Models\ServiceVisit;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $service_visit_id
 * @property int $service_details_id
 * @property int $service_status_id
 */
class ServiceVisitDetails extends Model
{

    protected $fillable = [
        'service_visit_id', 'service_details_id', 'service_status_id',
    ];


    public function visit()
    {
        return $this->belongsTo(ServiceVisit::class, 'service_visit_id', 'id');
    }

    public function details()
    {
        return $this->belongsTo(ServiceDetails::class, 'service_details_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(ServiceStatus::class, 'service_status_id', 'id');
    }
}
