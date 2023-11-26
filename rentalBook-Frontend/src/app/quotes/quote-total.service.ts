import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { BehaviorSubject } from 'rxjs';
import { IQuotesCollection } from './quotes-collection.interface';

@Injectable({
  providedIn: 'root'
})
export class QuoteTotalService {

  total_quotesSub: BehaviorSubject<IQuotesCollection> = new BehaviorSubject(null);

  preSelectStatus: BehaviorSubject<string> = new BehaviorSubject(null);

  constructor(protected http: HttpClient) {
  }

}
