<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** @property int $id
 * @property string $title
 * @property string $international_title
 **/
class OwnershipTypes extends Model
{
    // does not affects something but for the future
    protected $fillable = ['id','title','international_title'];

    protected $table = 'ownership_types';
}
