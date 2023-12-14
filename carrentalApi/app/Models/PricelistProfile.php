<?php

namespace App\Models;

use App\Traits\ModelBelongsToPricelistTrait;
use App\Traits\ModelIsLocalizedProfileTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int         $id
 * @property string      $created_at
 * @property string      $updated_at
 * @property int         $language_id
 * @property int         $pricelist_id
 * @property string      $title
 * @property string|null $description
 */
class PricelistProfile extends Model
{
    use ModelIsLocalizedProfileTrait;
    use ModelBelongsToPricelistTrait;
}
