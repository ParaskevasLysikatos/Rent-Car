<?php

use App\Contact;
use App\Driver;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    private function copyDriversInfo() {
        $drivers = Driver::all();
        foreach ($drivers as $driver) {
            $contact = new Contact();
            $contact->firstname                 = $driver->getOriginal('firstname');
            $contact->lastname                  = $driver->getOriginal('lastname');
            $contact->email                     = $driver->getOriginal('email');
            $contact->phone                     = $driver->getOriginal('phone');
            $contact->address                   = $driver->getOriginal('address');
            $contact->zip                       = $driver->getOriginal('zip');
            $contact->city                      = $driver->getOriginal('city');
            $contact->country                   = $driver->getOriginal('country');
            $contact->birthday                  = $driver->getOriginal('birthday');
            $contact->identification_number     = $driver->getOriginal('identification_number');
            $contact->identification_country    = $driver->getOriginal('identification_country');
            $contact->identification_created    = $driver->getOriginal('identification_created');
            $contact->identification_expire     = $driver->getOriginal('identification_expire');
            $contact->save();
            $driver->contact_id                 = $contact->id;
            $driver->unsetEventDispatcher();
            $driver->save();
        }
    }

    private function copyContactInfo() {
        $drivers = Driver::all();
        foreach ($drivers as $driver) {
            $contact = Contact::find($driver->contact_id);

            $driver->firstname                  = $contact->firstname;
            $driver->lastname                   = $contact->lastname;
            $driver->email                      = $contact->email;
            $driver->phone                      = $contact->phone;
            $driver->address                    = $contact->address;
            $driver->zip                        = $contact->zip;
            $driver->city                       = $contact->city;
            $driver->country                    = $contact->country;
            $driver->birthday                   = $contact->birthday;
            $driver->identification_number      = $contact->identification_number;
            $driver->identification_country     = $contact->identification_country;
            $driver->identification_created     = $contact->identification_created;
            $driver->identification_expire      = $contact->identification_expire;
            $driver->unsetEventDispatcher();
            $driver->save();
        }
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->softDeletes();

            $table->string('firstname');
            $table->string('lastname');

            $table->string('email');
            $table->string('phone');
            $table->string('address')->nullable();
            $table->string('zip')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->date('birthday')->nullable();

            $table->string('identification_number')->nullable();
            $table->string('identification_country')->nullable();
            $table->string('identification_created')->nullable();
            $table->string('identification_expire')->nullable();
        });

        Schema::table('drivers', function(Blueprint $table) {
            $table->bigInteger('contact_id')->unsigned()->nullable();
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade')->onUpdate('cascade');
        });

        $this->copyDriversInfo();

        Schema::table('drivers', function(Blueprint $table) {
            $table->bigInteger('contact_id')->unsigned()->change();

            $table->dropColumn('firstname');
            $table->dropColumn('lastname');
            $table->dropColumn('email');
            $table->dropColumn('phone');
            $table->dropColumn('address');
            $table->dropColumn('zip');
            $table->dropColumn('city');
            $table->dropColumn('country');
            $table->dropColumn('birthday');
            $table->dropColumn('identification_number');
            $table->dropColumn('identification_country');
            $table->dropColumn('identification_created');
            $table->dropColumn('identification_expire');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('drivers', function(Blueprint $table) {
            $table->string('firstname');
            $table->string('lastname');

            $table->string('email');
            $table->string('phone');
            $table->string('address')->nullable();
            $table->string('zip')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->date('birthday')->nullable();

            $table->string('identification_number')->nullable();
            $table->string('identification_country')->nullable();
            $table->string('identification_created')->nullable();
            $table->string('identification_expire')->nullable();
        });
        $this->copyContactInfo();
        Schema::table('drivers', function(Blueprint $table) {
            $table->date('birthday')->change();
            $table->dropForeign('drivers_contact_id_foreign');
            $table->dropColumn('contact_id');
        });
        Schema::dropIfExists('contacts');
    }
}
