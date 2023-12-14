<?php

namespace App\Models;

use App\Traits\ModelHasProfileTitleAttributeTrait;
use App\Traits\ModelHasSlugTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Lang;

/**
 * @property int        $id
 * @property string     $created_at
 * @property string     $updated_at
 * @property string     $deleted_at
 * @property string     $slug
 * @property boolean    $rental
 * @property boolean    $commission
 * @property boolean    $extras_booking
 * @property boolean    $extras_rental
 *
 */
class Program extends Model
{
    use SoftDeletes;
    use ModelHasSlugTrait;
    use ModelHasProfileTitleAttributeTrait;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function profiles()
    {
        return $this->hasMany(ProgramProfile::class, 'program_id', 'id');
    }

    public function getPrintTitleAttribute()
    {
        return $this->getPrintTitle(Lang::getLocale());
    }

    public function getPrintTitle(string $language_id)
    {
        return $this->profiles()->where('language_id', $language_id)->first()->print_title;
    }

    public function getProfileByLanguageId(string $language_id)
    {
        return $this->profiles()->where('language_id', $language_id)->first();
    }
}
