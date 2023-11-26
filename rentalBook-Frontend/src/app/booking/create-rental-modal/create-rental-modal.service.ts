import { IBookingItem } from './../../booking-item/booking-item.interface';
import { Injectable, Injector } from '@angular/core';
import { AbstractControl, FormArray, FormGroup } from '@angular/forms';
import { BehaviorSubject } from 'rxjs';
import { IPayers } from 'src/app/rental/rental-form/rental-form.component';
import { IVehicle } from 'src/app/vehicle/vehicle.interface';
import { IBooking } from '../booking.interface';
import { ISummaryCharges } from 'src/app/summary-charges/summary-charges.interface';

@Injectable({
  providedIn: 'root'
})
export class CreateRentalModalService {

  constructor() {
  }

  createRenSubject: BehaviorSubject<IBooking> = new BehaviorSubject(null);
  summaryChargesSub: BehaviorSubject<ISummaryCharges> = new BehaviorSubject(null);
  summaryChargesItemsSub: BehaviorSubject<IBookingItem[]> = new BehaviorSubject(null);

  stationOutSub: BehaviorSubject<string> = new BehaviorSubject(null);
  stationInSub: BehaviorSubject<string> = new BehaviorSubject(null);

  driverSub: BehaviorSubject<{id:'',name:'',phone:''}> = new BehaviorSubject(null);
  vehicleSub: BehaviorSubject<IVehicle> = new BehaviorSubject(null);
  sourceSub: BehaviorSubject<string> = new BehaviorSubject(null);
  agentSub: BehaviorSubject<string> = new BehaviorSubject(null);

  typeSub: BehaviorSubject<string> = new BehaviorSubject(null);
  payersSub: BehaviorSubject<IPayers> = new BehaviorSubject(null);

  callSaveSubject: BehaviorSubject<boolean> = new BehaviorSubject(false);

}
