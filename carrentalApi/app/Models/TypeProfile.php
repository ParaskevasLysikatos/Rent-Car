<?php

namespace App;

use App\Traits\ModelBelongsToTypeTrait;
use App\Traits\ModelIsLocalizedProfileTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int         $id
 * @property string      $created_at
 * @property string      $updated_at
 * @property int         $language_id
 * @property int         $type_id
 * @property string      $title
 * @property string|null $description
 */
class TypeProfile extends Model
{
    use ModelIsLocalizedProfileTrait;
    use ModelBelongsToTypeTrait;

    protected $fillable = [
        'type_id', 'language_id',
    ];
}
