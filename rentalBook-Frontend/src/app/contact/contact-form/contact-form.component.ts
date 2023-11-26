import { Component, Injector, OnInit } from '@angular/core';
import { Validators } from '@angular/forms';
import { AbstractFormComponent } from 'src/app/abstract-form/abstract-form.component';


@Component({
  selector: 'app-contact-form',
  templateUrl: './contact-form.component.html',
  styleUrls: ['./contact-form.component.scss']
})
export class ContactFormComponent extends AbstractFormComponent implements OnInit {
  form = this.fb.group({
    id: null,
    firstname: [,Validators.required],
    lastname: [,Validators.required],
    email: [],
    phone: [, Validators.required],
    address: [],
    zip: [],
    city: [],
    country: [],
    birthday: [],
    identification_number: [],
    identification_country: [],
    identification_created: [],
    identification_expire: [],
    afm: [],
    mobile: [],
  });

  constructor(protected injector: Injector) {
    super(injector);
  }
}
