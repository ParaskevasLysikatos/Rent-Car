import { Component, Injector, ViewChild } from '@angular/core';
import { CreateFormComponent } from 'src/app/create-form/create-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { BookingSourceFormComponent } from '../booking-source-form/booking-source-form.component';
import { IBookingSource } from '../booking-source.interface';
import { BookingSourceService } from '../booking-source.service';

@Component({
  selector: 'app-create-booking-source',
  templateUrl: './create-booking-source.component.html',
  styleUrls: ['./create-booking-source.component.scss'],
  providers: [{provide: ApiService, useClass: BookingSourceService}]
})
export class CreateBookingSourceComponent extends CreateFormComponent<IBookingSource> {
  @ViewChild(BookingSourceFormComponent, {static: true}) formComponent!: BookingSourceFormComponent;
}
