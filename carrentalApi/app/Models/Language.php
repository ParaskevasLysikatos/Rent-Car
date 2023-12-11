<?php

namespace App;

use App\Filters\LanguageFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property string $id
 * @property string $slug
 * @property string $title
 * @property int    $order
 * @property string $title_international
 */
class Language extends Model
{
    public $timestamps   = FALSE;
    public $incrementing = FALSE;

    protected $fillable = [
        'id', 'order',
    ];

    public function scopeFilter(Builder $builder, $request) // v2 filters, see filters folder
    {
        return (new LanguageFilter($request))->filter($builder);
    }


    public function setIdAttribute($value)
    {
        $value = strval($value);
        $value = trim($value);
        $value = Str::slug($value, '-');

        $this->attributes['id'] = $value;
    }
}
