<?php

namespace App;

use App\Filters\CompanyFilter;
use App\Traits\ModelHasBalancesTrait;
use App\Traits\ModelHasCommentsTrait;
use App\Traits\ModelHasDriversTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Company
 *
 * @property int    $id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property string $name
 * @property string $afm
 * @property string|null $doy
 * @property string|null $country
 * @property string|null $city
 * @property string|null $job
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $website
 * @property float $balance
 * @property string|null $title
 * @property string|null $address
 */
class Company extends Model
{
    use ModelHasDriversTrait;
    use ModelHasBalancesTrait;
    use ModelHasCommentsTrait;
    use SoftDeletes;

    protected $fillable = [
        'id',
        'name',
        'title',
        'afm',
        'doy',
        'country',
        'city',
        'job',
        'phone',
        'phone_2',
        'email',
        'website',
        'address',
        'zip_code',
        'comments',
        'main',
        'mite_number',
        'foreign_afm'
    ];

    public function scopeFilter(Builder $builder, $request) // v2 filters, see filters folder
    {
        return (new CompanyFilter($request))->filter($builder);
    }

    public function rentals() {
        return $this->hasMany(Rental::class);
    }

    public function bookings() // company page will hold connected bookings
    {
        return $this->hasMany(Booking::class);
    }

    public function quotes()// same
    {
        return $this->hasMany(Quote::class);
    }

    public function agent() { // agent can have a company
        return $this->hasOne(Agent::class);
    }

    public function invoices()
    {
        return $this->morphMany(Invoice::class, 'invoicee')->orderBy('created_at', 'DESC');
    }
}
