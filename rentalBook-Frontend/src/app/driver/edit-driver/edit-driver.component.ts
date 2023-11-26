import { Component, Injector, ViewChild } from '@angular/core';
import { EditFormComponent } from 'src/app/edit-form/edit-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { DriverFormComponent } from '../driver-form/driver-form.component';
import { IDriver } from '../driver.interface';
import { DriverService } from '../driver.service';

@Component({
  selector: 'app-edit-driver',
  templateUrl: './edit-driver.component.html',
  styleUrls: ['./edit-driver.component.scss'],
  providers: [{provide: ApiService, useClass: DriverService}]
})
export class EditDriverComponent extends EditFormComponent<IDriver> {
  @ViewChild(DriverFormComponent, {static: true}) formComponent!: DriverFormComponent;

  afterDataLoad(res:IDriver) {
    this.formComponent.loadSrv.loadingOn();
    this.formComponent.contactData=res.contact;
    this.formComponent.contactUser=res.user;
    //this.formComponent.driverId=res.id;
    this.formComponent.rentals = res.rentals;
    this.formComponent.invoices=res.invoices;

    this.formComponent.bookings = res.bookings;
    this.formComponent.quotes = res.quotes;

    //this.formComponent.form.controls['lastname'].patchValue(res.contact.lastname);
    this.formComponent.form.patchValue({'lastname':res.contact.lastname,
      'firstname':res.contact.firstname,
      'phone':res.contact.phone,
      'mobile':res.contact.mobile,
      'email':res.contact.email,
      'country':res.contact.country,
      'city':res.contact.city,
      'address':res.contact.address,
      'zip':res.contact.zip,
      'birth_place':res.contact.birth_place,
      // 'how_old':res.contact.how_old,
      'birthday':res.contact.birthday,
      'afm':res.contact.afm,
      'identification_number':res.contact.identification_number,
      'identification_created':res.contact.identification_created,
      'identification_expire':res.contact.identification_expire,
      'identification_country':res.contact.identification_country,
    });
    this.formComponent.loadSrv.loadingOff();
  }

}
