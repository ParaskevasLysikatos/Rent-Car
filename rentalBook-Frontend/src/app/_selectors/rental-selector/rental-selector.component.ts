import { Component, forwardRef, Injector } from '@angular/core';
import { NG_VALUE_ACCESSOR } from '@angular/forms';
import { CreateRentalComponent } from 'src/app/rental/create-rental/create-rental.component';
import { EditRentalComponent } from 'src/app/rental/edit-rental/edit-rental.component';
import { IRental } from 'src/app/rental/rental.interface';
import { RentalService } from 'src/app/rental/rental.service';
import { ApiService } from 'src/app/_services/api-service.service';
import { AbstractSelectorComponent } from '../abstract-selector/abstract-selector.component';

@Component({
  selector: 'app-rental-selector',
  templateUrl: './rental-selector.component.html',
  styleUrls: ['./rental-selector.component.scss'],
  providers: [
    {
      provide: NG_VALUE_ACCESSOR,
      useExisting: forwardRef(() => RentalSelectorComponent),
      multi: true
    },
    {
      provide: AbstractSelectorComponent,
      useExisting: RentalSelectorComponent
    },
    {provide: ApiService, useClass: RentalService}
  ]
})
export class RentalSelectorComponent extends AbstractSelectorComponent<IRental> {
  readonly EditComponent = EditRentalComponent;
  readonly CreateComponent = CreateRentalComponent;
  label = 'Μισθώσεις';
}
