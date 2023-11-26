import { Component, forwardRef, Injector } from '@angular/core';
import { NG_VALUE_ACCESSOR } from '@angular/forms';
import { CreateContactComponent } from 'src/app/contact/create-contact/create-contact.component';
import { EditContactComponent } from 'src/app/contact/edit-contact/edit-contact.component';
import { IContact } from 'src/app/contact/contact.interface';
import { ContactService } from 'src/app/contact/contact.service';
import { AbstractSelectorComponent } from '../abstract-selector/abstract-selector.component';
import { ApiService } from 'src/app/_services/api-service.service';

@Component({
  selector: 'app-contact-selector',
  templateUrl: './contact-selector.component.html',
  styleUrls: ['./contact-selector.component.scss'],
  providers: [
    {
      provide: NG_VALUE_ACCESSOR,
      useExisting: forwardRef(() => ContactSelectorComponent),
      multi: true
    },
    {
      provide: AbstractSelectorComponent,
      useExisting: ContactSelectorComponent
    },
    {provide: ApiService, useClass: ContactService}
  ]
})
export class ContactSelectorComponent extends AbstractSelectorComponent<IContact> {
  readonly EditComponent = EditContactComponent;
  readonly CreateComponent = CreateContactComponent;
  label = 'Επαφή';
}
