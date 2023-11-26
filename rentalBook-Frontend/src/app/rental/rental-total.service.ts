import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { BehaviorSubject } from 'rxjs';
import { IRentalCollection } from './rental-collection.interface';


export interface RenBookmark {
  date_from_out: string;
  date_to_out: string;
  date_from_in: string;
  date_to_in: string;
}

@Injectable({
  providedIn: 'root'
})

export class RentalTotalService {

  total_rentalSub: BehaviorSubject<IRentalCollection> = new BehaviorSubject(null);

  bookmarkRental: BehaviorSubject<RenBookmark> = new BehaviorSubject(null);

  preSelectStatus: BehaviorSubject<string>= new BehaviorSubject(null);

  constructor(protected http: HttpClient) {
  }

}
