import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { BehaviorSubject } from 'rxjs';
import { IBookingCollection } from './booking-collection.interface';


@Injectable({
  providedIn: 'root'
})
export class TotalBookingService {

  total_bookingSub: BehaviorSubject<IBookingCollection> = new BehaviorSubject(null);
  preSelectStatus: BehaviorSubject<string> = new BehaviorSubject<string>(null);

  constructor(protected http: HttpClient) {
  }

}
