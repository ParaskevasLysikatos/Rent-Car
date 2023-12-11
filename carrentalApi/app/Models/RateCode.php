<?php

namespace App;

use App\Filters\RateCodeFilter;
use App\Traits\ModelHasSlugTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RateCode extends Model
{
    use SoftDeletes;
    use ModelHasSlugTrait;

    protected $fillable = [
        'id',
    ];

    public function scopeFilter(Builder $builder, $request) // v2 filters, see filters folder
    {
        return (new RateCodeFilter($request))->filter($builder);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function profiles()
    {
        return $this->hasMany(RateCodesProfile::class, 'rate_code_id', 'id');
    }

    public function getProfileByLanguageId(string $language_id)
    {
        return RateCodesProfile::where('rate_code_id', $this->id)
                             ->where('language_id', $language_id)
                             ->first();
    }
}
