import { HttpClient } from '@angular/common/http';
import { Injectable, Injector } from '@angular/core';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { ICombined } from './combined.interface';
import { ICombinedVehicles } from './combinedVehicles.interface';
import { delay, Observable, retry, shareReplay } from 'rxjs';
import { ICombinedBookingSources } from './combinedBookingSources.interface';
import { ICombinedUsers } from './combinedUsers.interface';
import { ICombinedTypes } from './combinedTypes.interface';
import { ICombinedAgents } from './combinedAgents.interface';
import { ICombinedInvoices } from './combinedInvoices.interface';
import { ICombinedPayments } from './combinedPayments.interface';
import { ICombinedQuotes } from './combinedQuotes.interface';
import { ICombinedBookings } from './combinedBookings.interface';
import { ICombinedRentals } from './combinedRentals.interface';

@Injectable({
  providedIn: 'root'
})
export class CombinedService{
  url = `${env.apiUrl}/home/combined`;

  constructor(protected http: HttpClient) {}

  get(): Observable<ICombined> {
    return this.http.get<ICombined>(this.url).pipe(
      shareReplay(1, 5 * 60 * 1000),// 5 min, in miillisec
      retry(2), // you retry 3 times
      delay(1000) // each retry will start after 1 second,

    );
  }


  getVehicles(): Observable<ICombinedVehicles> {
    return this.http.get<ICombinedVehicles>(this.url + 'Vehicles').pipe(
      shareReplay(1, 5 * 60 * 1000),// 5 min, in miillisec
      retry(2), // you retry 3 times
      delay(1000) // each retry will start after 1 second,

    );
  }


  getBookingSources(): Observable<ICombinedBookingSources> {
    return this.http.get<ICombinedBookingSources>(this.url + 'BookingSources').pipe(
      shareReplay(1, 5 * 60 * 1000),// 5 min, in miillisec
      retry(2), // you retry 3 times
      delay(1000) // each retry will start after 1 second,

    );
  }


  getUsers(): Observable<ICombinedUsers> {
    return this.http.get<ICombinedUsers>(this.url + 'Users').pipe(
      shareReplay(1, 5 * 60 * 1000),// 5 min, in miillisec
      retry(2), // you retry 3 times
      delay(1000) // each retry will start after 1 second,

    );
  }


  getTypes(): Observable<ICombinedTypes> {
    return this.http.get<ICombinedTypes>(this.url + 'Types').pipe(
      shareReplay(1, 5 * 60 * 1000),// 5 min, in miillisec
      retry(2), // you retry 3 times
      delay(1000) // each retry will start after 1 second,

    );
  }


  getAgents(): Observable<ICombinedAgents> {
    return this.http.get<ICombinedAgents>(this.url + 'Agents').pipe(
      shareReplay(1, 5 * 60 * 1000),// 5 min, in miillisec
      retry(2), // you retry 3 times
      delay(1000) // each retry will start after 1 second,

    );
  }


  getInvoices(): Observable<ICombinedInvoices> {
    return this.http.get<ICombinedInvoices>(this.url + 'Invoices').pipe(
      shareReplay(1, 5 * 60 * 1000),// 5 min, in miillisec
      retry(2), // you retry 3 times
      delay(1000) // each retry will start after 1 second,

    );
  }


  getPayments(): Observable<ICombinedPayments> {
    return this.http.get<ICombinedPayments>(this.url + 'Payments').pipe(
      shareReplay(1, 5 * 60 * 1000),// 5 min, in miillisec
      retry(2), // you retry 3 times
      delay(1000) // each retry will start after 1 second,

    );
  }

  getQuotes(): Observable<ICombinedQuotes> {
    return this.http.get<ICombinedQuotes>(this.url + 'Quotes').pipe(
      shareReplay(1, 5 * 60 * 1000),// 5 min, in miillisec
      retry(2), // you retry 3 times
      delay(1000) // each retry will start after 1 second,

    );
  }

  getBookings(): Observable<ICombinedBookings> {
    return this.http.get<ICombinedBookings>(this.url + 'Bookings').pipe(
      shareReplay(1, 5 * 60 * 1000),// 5 min, in miillisec
      retry(2), // you retry 3 times
      delay(1000) // each retry will start after 1 second,
    );
  }


  getRentals(): Observable<ICombinedRentals> {
    return this.http.get<ICombinedRentals>(this.url + 'Rentals').pipe(
      shareReplay(1, 5 * 60 * 1000),// 5 min, in miillisec
      retry(2), // you retry 3 times
      delay(1000) // each retry will start after 1 second,
    );
  }



}
