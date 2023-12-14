<?php

namespace App\Observers;

use App\Models\Contact;

class ContactObserver
{
    public function deleting(Contact $contact) {
        $contact->driver->delete();
    }
}
