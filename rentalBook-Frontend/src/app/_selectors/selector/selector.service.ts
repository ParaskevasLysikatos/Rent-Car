import { Injectable } from '@angular/core';
import { BehaviorSubject, Observable, Subject } from 'rxjs';
import { IAgent } from 'src/app/agent/agent.interface';
import { IBookingSource } from 'src/app/booking-source/booking-source.interface';
import { ICompany } from 'src/app/company/company.interface';
import { IDriver } from 'src/app/driver/driver.interface';
import { IRental } from 'src/app/rental/rental.interface';
import { IStation } from 'src/app/stations/station.interface';
import { ITypes } from 'src/app/types/types.interface';
import { IVehicle } from 'src/app/vehicle/vehicle.interface';

@Injectable({
  providedIn: 'root'
})
export class SelectorService {
  searched: Subject<void> = new Subject();
  searched$ = this.searched.asObservable();
  searchTerm: Subject<string> = new Subject();
  searchTerm$ = this.searchTerm.asObservable();

  searchRental: BehaviorSubject<IRental> = new BehaviorSubject(null);
  searchDriver: BehaviorSubject<IDriver> = new BehaviorSubject(null);
  searchCompany: BehaviorSubject<ICompany> = new BehaviorSubject(null);

  searchStation: BehaviorSubject<IStation> = new BehaviorSubject(null);

  searchSource: BehaviorSubject<IBookingSource> = new BehaviorSubject(null);
  searchAgent: BehaviorSubject<IAgent> = new BehaviorSubject(null);
  searchSubAccount: BehaviorSubject<any> = new BehaviorSubject(null);

  searchGroup: BehaviorSubject<ITypes> = new BehaviorSubject(null);
  searchVehicle: BehaviorSubject<IVehicle> = new BehaviorSubject(null);

  createNewObjDriver: BehaviorSubject<boolean> = new BehaviorSubject(false);
  createNewObjSource: BehaviorSubject<boolean> = new BehaviorSubject(false);
  createNewObjCompany: BehaviorSubject<boolean> = new BehaviorSubject(false);
  createNewObjAgent: BehaviorSubject<boolean> = new BehaviorSubject(false);

  constructor() { }

  search(term: string): void {
    this.searchTerm.next(term);
  }

  onSearch(): Observable<string> {
    return this.searchTerm$;
  }

  finishSearch(): void {
    this.searched.next();
  }

  onFinishSearch(): Observable<void> {
    return this.searched$;
  }


  searchDriverTemp: BehaviorSubject<IDriver> = new BehaviorSubject(null);
  searchCompanyTemp: BehaviorSubject<ICompany> = new BehaviorSubject(null);

  searchStationTemp: BehaviorSubject<IStation> = new BehaviorSubject(null);

  searchSourceTemp: BehaviorSubject<IBookingSource> = new BehaviorSubject(null);
  searchAgentTemp: BehaviorSubject<IAgent> = new BehaviorSubject(null);
  searchSubAccountTemp: BehaviorSubject<any> = new BehaviorSubject(null);

  searchGroupTemp: BehaviorSubject<ITypes> = new BehaviorSubject(null);
  searchVehicleTemp: BehaviorSubject<IVehicle> = new BehaviorSubject(null);



}
