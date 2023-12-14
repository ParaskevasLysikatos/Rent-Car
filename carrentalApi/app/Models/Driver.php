<?php

namespace App\Models;

use App\Filters\DriverFilter;
use App\Traits\ModelCreatesActionLogsTrait;
use App\Traits\ModelHasBalancesTrait;
use App\Traits\ModelHasDocumentsTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $notes
 * @property string|null $licence_number
 * @property string|null $licence_country
 * @property string|null $licence_created
 * @property string|null $licence_expire
 * @property string $role
 * @property int|null $contact_id
 * @property float $balance
 * @property-read mixed $address
 * @property-read mixed $afm
 * @property-read mixed $birthday
 * @property-read mixed $city
 * @property-read mixed $country
 * @property-read mixed $email
 * @property-read mixed $firstname
 * @property-read mixed $full_name
 * @property-read mixed $identification_country
 * @property-read mixed $identification_created
 * @property-read mixed $identification_expire
 * @property-read mixed $identification_number
 * @property-read mixed $lastname
 * @property-read mixed $phone
 * @property-read mixed $zip
 */
class Driver extends Model
{
    use SoftDeletes;
    use ModelCreatesActionLogsTrait;
    use ModelHasBalancesTrait;
    use ModelHasDocumentsTrait;

    const AVAILABLE_ROLES = [
        'Customer',
        'Employee'
    ];

    protected $fillable = [
        'id',
        'firstname',
        'lastname',
        'email',
        'phone',
        'address',
        'zip',
        'city',
        'country',
        'birthday',
        'fly_number',
        'notes',
        'licence_number',
        'licence_country',
        'licence_created',
        'licence_expire',
        'identification_number',
        'identification_country',
        'identification_created',
        'identification_expire',
        'role',
        'contact_id'
    ];

    protected $appends = ['full_name'];

    public function scopeFilter(Builder $builder, $request) // v2 filters, see filters folder
    {
        return (new DriverFilter($request))->filter($builder);
    }

    public function setRoleAttribute($role) {
        $this->attributes['role'] = strtolower($role);
    }

    public function getDocumentsPath()
    {
        return 'drivers/driver-' . $this->id . '/';
    }

    public function getInitialFileName()
    {
        return 'driverId';
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function companies()
    {
        return $this->morphedByMany(Company::class, 'driver_link');
    }

    public function contact() {
        return $this->belongsTo(Contact::class);
    }


    public function getFirstnameAttribute() {
        return $this->contact->firstname ?? '';
    }

    public function getLastnameAttribute() {
        return $this->contact->lastname ?? '';
    }

    public function getEmailAttribute() {
        return $this->contact->email ?? '';
    }

    public function getPhoneAttribute() {
        return $this->contact->phone ?? '';
    }

    public function getCountryAttribute() {
        return $this->contact->country ?? '';
    }

    public function getCityAttribute() {
        return $this->contact->city ?? '';
    }

    public function getAddressAttribute() {
        return $this->contact->address ?? '';
    }

    public function getZipAttribute() {
        return $this->contact->zip ?? '';
    }

    public function getBirthPlaceAttribute() {
        return $this->contact->birth_place ?? '';
    }

    public function getBirthdayAttribute() {
        return $this->contact->birthday ?? '';
    }

    public function getIdentificationNumberAttribute() {
        return $this->contact->identification_number ?? '';
    }

    public function getIdentificationCountryAttribute() {
        return $this->contact->identification_country ?? '';
    }

    public function getIdentificationCreatedAttribute() {
        return $this->contact->identification_created ?? '';
    }

    public function getIdentificationExpireAttribute() {
        return $this->contact->identification_expire ?? '';
    }

    public function getFullNameAttribute(){
        return $this->contact->full_name ?? '';
    }

    public function getAfmAttribute(){
        return $this->contact->afm ?? '';
    }

    public function getMobileAttribute(){
        return $this->contact->mobile ?? '';
    }

    public function getFullName(){
        return $this->full_name;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\morphedByMany
     */
    public function users() {
        return $this->morphedByMany(User::class, 'driver_link')->orderBy('id');
    }

    public function user() {
        return $this->users()->first();
    }

    public function rentals_primary() {
        return $this->hasMany(Rental::class, 'driver_id');
    }

    public function rentals_secondary() {
        return $this->morphedByMany(Rental::class, 'driver_link');
    }


// hold information for connected bookings and quotes in driver page
    public function bookings_primary()
    {
        return $this->hasMany(Booking::class, 'customer_id');
    }

    public function bookings_secondary()
    {
        return $this->morphedByMany(Booking::class, 'driver_link');
    }

    public function quotes_primary()
    {
        return $this->hasMany(Quote::class, 'customer_id');
    }

    public function quotes_secondary()
    {
        return $this->morphedByMany(Quote::class, 'driver_link');
    }
    //end of extra information

    public function getRentalsAttribute() {
        return $this->rentals_primary->merge($this->rentals_secondary)->sortByDesc('checkout_datetime');
    }

    public function invoices() {
        return $this->morphMany(Invoice::class, 'invoicee')->orderBy('created_at', 'DESC');
    }

    /**
     * Handle companies
     *
     * @param int[] $companies - Ids of App\Company
     * @return void
     */
    public function handleCompanies($companies) {
        if (!is_array($companies)) {
            $companies = [];
        }

        foreach ($companies as $company) {
            $company = Company::find($company);
            $company->addDrivers([$this->id]);
        }

        $existingCompanies = $this->companies()->get()->pluck('id');
        $companiesToRemove = array_diff($existingCompanies->toArray(), $companies);

        foreach ($companiesToRemove as $company) {
            $company = Company::find($company);
            $company->removeDrivers([$this->id]);
        }
    }

    public function getCustomerIdAttribute() {
        return get_class($this).'-'.$this->id;
    }
}
