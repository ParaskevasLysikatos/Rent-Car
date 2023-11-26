import { Component, Injector, ViewChild } from '@angular/core';
import { CreateFormComponent } from 'src/app/create-form/create-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { ContactFormComponent } from '../contact-form/contact-form.component';
import { IContact } from '../contact.interface';
import { ContactService } from '../contact.service';

@Component({
  selector: 'app-create-contact',
  templateUrl: './create-contact.component.html',
  styleUrls: ['./create-contact.component.scss'],
  providers: [{provide: ApiService, useClass: ContactService}]
})
export class CreateContactComponent extends CreateFormComponent<IContact> {
  @ViewChild(ContactFormComponent, {static: true}) formComponent!: ContactFormComponent;
}
