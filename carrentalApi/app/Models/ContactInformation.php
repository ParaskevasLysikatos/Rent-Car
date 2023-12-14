<?php

namespace App\Models;

/**
 * @property int         $id
 * @property string      $created_at
 * @property string      $updated_at
 * @property string|null $fullname
 * @property string|null $email
 * @property string|null $mobile
 * @property string|null $phone
 * @property string|null $fax
 * @property string|null $address
 * @property string|null $postcode
 * @property string|null $city
 * @property string|null $country
 * @property string|null $comments
 */

use App\Exceptions\InvalidEmailException;
use App\Traits\ModelHasCommentsTrait;
use Illuminate\Database\Eloquent\Model;

class ContactInformation extends Model
{
    use ModelHasCommentsTrait;

    public function setEmailAttribute($value)
    {
        $value = is_scalar($value) ? trim(strval($value)) : '';

        if (!empty($value)) {
            $value = strtolower($value);

            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                throw new InvalidEmailException('email is invalid');
            }
        } else {
            $value = NULL;
        }

        $this->attributes['email'] = $value;
    }
}
