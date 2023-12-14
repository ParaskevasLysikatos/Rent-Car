<?php

namespace App\Models;

use App\Filters\VehicleStatusFilter;
use App\Traits\ModelHasProfileTitleAttributeTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Model
{
    use SoftDeletes;
    use ModelHasProfileTitleAttributeTrait;

    protected $table = 'vehicle_status';
    protected $fillable = ['id'];
    const STATUS_ACTIVE = 'active';

    public function scopeFilter(Builder $builder, $request) // v2 filters, see filters folder
    {
        return (new VehicleStatusFilter($request))->filter($builder);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function profiles()
    {
        return $this->hasMany(StatusProfile::class, 'vehicle_status_id', 'id');
    }


    public function getProfileByLanguageId(string $language_id)
    {
        return StatusProfile::where('vehicle_status_id', $this->id)
            ->where('language_id', $language_id)
            ->first();

   }
}
