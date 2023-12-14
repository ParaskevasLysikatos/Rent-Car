<?php

namespace App\Models;

use App\Filters\TagFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Tag
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $title
 */
class Tag extends Model
{
    protected $fillable = ['title'];

    public function tag_links() {
        return $this->hasMany(TagLink::class);
    }

    public function scopeFilter(Builder $builder, $request) // v2 filters, see filters folder
    {
        return (new TagFilter($request))->filter($builder);
    }
}
