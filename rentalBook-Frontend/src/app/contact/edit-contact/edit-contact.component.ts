import { Component, Injector, ViewChild } from '@angular/core';
import { EditFormComponent } from 'src/app/edit-form/edit-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { ContactFormComponent } from '../contact-form/contact-form.component';
import { IContact } from '../contact.interface';
import { ContactService } from '../contact.service';

@Component({
  selector: 'app-edit-contact',
  templateUrl: './edit-contact.component.html',
  styleUrls: ['./edit-contact.component.scss'],
  providers: [{provide: ApiService, useClass: ContactService}]
})
export class EditContactComponent extends EditFormComponent<IContact> {
  @ViewChild(ContactFormComponent, {static: true}) formComponent!: ContactFormComponent;
}
