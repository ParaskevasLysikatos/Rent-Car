import { Component, forwardRef, Injector } from '@angular/core';
import { NG_VALUE_ACCESSOR } from '@angular/forms';
import { IBookingSource } from 'src/app/booking-source/booking-source.interface';
import { BookingSourceService } from 'src/app/booking-source/booking-source.service';
import { CreateBookingSourceComponent } from 'src/app/booking-source/create-booking-source/create-booking-source.component';
import { EditBookingSourceComponent } from 'src/app/booking-source/edit-booking-source/edit-booking-source.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { AbstractSelectorComponent } from '../abstract-selector/abstract-selector.component';

@Component({
  selector: 'app-source-selector',
  templateUrl: './source-selector.component.html',
  styleUrls: ['./source-selector.component.scss'],
  providers: [
    {
      provide: NG_VALUE_ACCESSOR,
      useExisting: forwardRef(() => SourceSelectorComponent),
      multi: true
    },
    {
      provide: AbstractSelectorComponent,
      useExisting: SourceSelectorComponent
    },
    {provide: ApiService, useClass: BookingSourceService}
  ]
})
export class SourceSelectorComponent extends AbstractSelectorComponent<IBookingSource> {
  readonly EditComponent = EditBookingSourceComponent;
  readonly CreateComponent = CreateBookingSourceComponent;
  label = 'Πηγή';
}
