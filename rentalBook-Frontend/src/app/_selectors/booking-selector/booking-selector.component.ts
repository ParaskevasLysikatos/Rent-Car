import { Component, forwardRef, Injector } from '@angular/core';
import { NG_VALUE_ACCESSOR } from '@angular/forms';
import { IBooking } from 'src/app/booking/booking.interface';
import { BookingService } from 'src/app/booking/booking.service';
import { CreateBookingComponent } from 'src/app/booking/create-booking/create-booking.component';
import { EditBookingComponent } from 'src/app/booking/edit-booking/edit-booking.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { AbstractSelectorComponent } from '../abstract-selector/abstract-selector.component';

@Component({
  selector: 'app-booking-selector',
  templateUrl: './booking-selector.component.html',
  styleUrls: ['./booking-selector.component.scss'],
  providers: [
    {
      provide: NG_VALUE_ACCESSOR,
      useExisting: forwardRef(() => BookingSelectorComponent),
      multi: true
    },
    {
      provide: AbstractSelectorComponent,
      useExisting: BookingSelectorComponent
    },
    {provide: ApiService, useClass: BookingService}
  ]
})
export class BookingSelectorComponent extends AbstractSelectorComponent<IBooking> {
  readonly EditComponent = EditBookingComponent;
  readonly CreateComponent = CreateBookingComponent;
  label = 'Κρατήσεις';
}
