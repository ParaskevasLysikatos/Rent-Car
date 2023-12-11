<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/** @property int $id
 * @property string $title
 * @property string $international_title
 **/
class UseTypes extends Model
{
       // does not affects something but for the future
    protected $fillable = ['id','title','international_title'];

    protected $table = 'use_types';
}
