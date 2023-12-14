<?php

namespace App\Traits;

use App\Models\Contact;
use Request;

trait ModelHasContactsTrait {

    public static function bootModelHasContactsTrait() {
        static::saved(function ($object) {
           // $object->addContacts(); //v1->v2 every model that has this trait, in controller on create method after save, should be invoked like $var->method();
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function contacts() {
        return $this->morphToMany(Contact::class, 'contact_link')->orderBy('id');
    }

    public function addContacts() {
        if (Request::has('contacts')) {
            $this->contacts()->detach();
            $contacts = Request::get('contacts');
            if ($contacts) {
                foreach ($contacts as $contact) {
                    $this->contacts()->attach($contact);
                }
            }
        }
    }
}
