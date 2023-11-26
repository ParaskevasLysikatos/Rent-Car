<?php

namespace App;

use App\Traits\ModelHasSlugTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * \App\Insurance
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string $slug
 */
class Insurance extends Model
{
    use SoftDeletes;
    use ModelHasSlugTrait;

    protected $fillable = [
        'id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function profiles()
    {
        return $this->hasMany(InsurancesProfile::class, 'insurance_profile_id', 'id');
    }

    public function getProfileByLanguageId(string $language_id)
    {
        return InsurancesProfile::where('insurance_id', $this->id)
                             ->where('language_id', $language_id)
                             ->first();
    }
}
