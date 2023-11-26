import { Injectable } from '@angular/core';
import { FormArray, FormGroup } from '@angular/forms';
import { BehaviorSubject } from 'rxjs';
import { IBookingItem } from 'src/app/booking-item/booking-item.interface';
import { IDocuments } from 'src/app/documents/documents.interface';
import { IOptions } from 'src/app/options/options.interface';
import { IPayers } from 'src/app/rental/rental-form/rental-form.component';
import { ISummaryCharges } from 'src/app/summary-charges/summary-charges.interface';
import { IQuotes } from '../quotes.interface';

@Injectable({
  providedIn: 'root'
})
export class CreateBookingModalService {

constructor() { }


  createBookSubject: BehaviorSubject<IQuotes> = new BehaviorSubject(null);
  summaryChargesSub: BehaviorSubject<ISummaryCharges> = new BehaviorSubject(null);
  summaryChargesItemsSub: BehaviorSubject<IBookingItem[]> = new BehaviorSubject(null);

  stationOutSub: BehaviorSubject<string> = new BehaviorSubject(null);
  stationInSub: BehaviorSubject<string> = new BehaviorSubject(null);

  driverSub: BehaviorSubject<string> = new BehaviorSubject(null);
  //vehicleSub: BehaviorSubject<IVehicle> = new BehaviorSubject(null);
  sourceSub: BehaviorSubject<string> = new BehaviorSubject(null);
  agentSub: BehaviorSubject<string> = new BehaviorSubject(null);

  typeSub: BehaviorSubject<string> = new BehaviorSubject(null);
  payersSub: BehaviorSubject<IPayers> = new BehaviorSubject(null);

  callSaveSubject: BehaviorSubject<boolean> = new BehaviorSubject(false);

}
