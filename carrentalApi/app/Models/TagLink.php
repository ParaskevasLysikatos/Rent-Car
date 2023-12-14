<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\TagLink
 *
 * @property int $id
 * @property int $tag_id
 * @property string $tag_link_type
 * @property int $tag_link_id
 */
class TagLink extends Model
{
    public $timestamps = false;
}
