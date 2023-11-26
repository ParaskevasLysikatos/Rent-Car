import { Component, Injector, ViewChild } from '@angular/core';
import { EditFormComponent } from 'src/app/edit-form/edit-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { BookingItemFormComponent } from '../booking-item-form/booking-item-form.component';
import { IBookingItem } from '../booking-item.interface';
import { BookingItemService } from '../booking-item.service';

@Component({
  selector: 'app-edit-booking-item',
  templateUrl: './edit-booking-item.component.html',
  styleUrls: ['./edit-booking-item.component.scss'],
  providers: [{provide: ApiService, useClass: BookingItemService}]
})
export class EditBookingItemComponent extends EditFormComponent<IBookingItem> {
  @ViewChild(BookingItemFormComponent, {static: true}) formComponent!: BookingItemFormComponent;
}
