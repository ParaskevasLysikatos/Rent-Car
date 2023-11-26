import { Component, Injector, ViewChild } from '@angular/core';
import { EditFormComponent } from 'src/app/edit-form/edit-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { BookingSourceFormComponent } from '../booking-source-form/booking-source-form.component';
import { IBookingSource } from '../booking-source.interface';
import { BookingSourceService } from '../booking-source.service';

@Component({
  selector: 'app-edit-booking-source',
  templateUrl: './edit-booking-source.component.html',
  styleUrls: ['./edit-booking-source.component.scss'],
  providers: [{provide: ApiService, useClass: BookingSourceService}]
})
export class EditBookingSourceComponent extends EditFormComponent<IBookingSource> {
  @ViewChild(BookingSourceFormComponent, {static: true}) formComponent!: BookingSourceFormComponent;
}
