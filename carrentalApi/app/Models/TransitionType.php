<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property string $title
 */
class TransitionType extends Model
{
    public $timestamps = FALSE;

      // does not affects something but for the future

    protected $fillable = [
        'title',
    ];
}
