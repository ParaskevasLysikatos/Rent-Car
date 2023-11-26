<?php

namespace App;

use App\Filters\ContactFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * \App\Contact
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property string $phone
 * @property string $mobile
 * @property string|null $address
 * @property string|null $zip
 * @property string|null $city
 * @property string|null $country
 * @property string|null $birthday
 * @property string|null $afm
 * @property string|null $identification_number
 * @property string|null $identification_country
 * @property string|null $identification_created
 * @property string|null $identification_expire
 * @property int|null $agent_id
 * @property-read mixed $full_name
 */
class Contact extends Model
{
    use SoftDeletes;

    protected $appends = [
        'full_name'
    ];

    protected $fillable = [
        'id'
    ];

     public function scopeFilter(Builder $builder, $request) // v2 filters, see filters folder
    {
        return (new ContactFilter($request))->filter($builder);
    }


    public function getFullNameAttribute(){
        return ucfirst($this->lastname)." ".ucfirst($this->firstname);
    }

    public function driver() {
        return $this->hasOne(Driver::class);
    }

    public function agent() {
        return $this->morphedByMany(Agent::class, 'contact_link');
    }

    public function agent_account() {
        return $this->morphedByMany(Agent::class, 'agent_contact');
    }

    public function getCustomerIdAttribute() {
        return get_class($this).'-'.$this->id;
    }
}
