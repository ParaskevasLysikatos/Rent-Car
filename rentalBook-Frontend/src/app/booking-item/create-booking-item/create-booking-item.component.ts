import { Component, Injector, ViewChild } from '@angular/core';
import { CreateFormComponent } from 'src/app/create-form/create-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { BookingItemFormComponent } from '../booking-item-form/booking-item-form.component';
import { IBookingItem } from '../booking-item.interface';
import { BookingItemService } from '../booking-item.service';

@Component({
  selector: 'app-create-booking-item',
  templateUrl: './create-booking-item.component.html',
  styleUrls: ['./create-booking-item.component.scss'],
  providers: [{provide: ApiService, useClass: BookingItemService}]
})
export class CreateBookingItemComponent extends CreateFormComponent<IBookingItem> {
  @ViewChild(BookingItemFormComponent, {static: true}) formComponent!: BookingItemFormComponent;
}
