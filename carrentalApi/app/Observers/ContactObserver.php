<?php

namespace App\Observers;

use App\Contact;

class ContactObserver
{
    public function deleting(Contact $contact) {
        $contact->driver->delete();
    }
}
