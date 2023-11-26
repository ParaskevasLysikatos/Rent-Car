import { Injectable } from '@angular/core';
import { BehaviorSubject, Observable, of, tap, concatMap, finalize } from 'rxjs';

@Injectable({ providedIn: 'root' })
export class MyLoadingService {

  loadingSubject: BehaviorSubject<boolean> = new BehaviorSubject<boolean>(false);

  loading$: Observable<boolean> = this.loadingSubject.asObservable();

  enterFirstTimePreview = new BehaviorSubject<boolean>(false);

  constructor() {
   // console.log("Loading service created ...");
  }

  showLoaderUntilCompleted<T>(obs$: Observable<T>): Observable<T> {
    return of(null)
      .pipe(
        tap(() => this.loadingOn()),
        concatMap(() => obs$),
        finalize(() => this.loadingOff())
      );
  }

  loadingOn() {
    this.loadingSubject.next(true);
    console.log("Loading service created ...");

  }

  loadingOff() {
    console.log("Loading service closed");
    this.loadingSubject.next(false);
  }

  loadingVeryLight() {
    this.loadingOn();
    setTimeout(() => this.loadingOff(), 1200);
  }

  loadingLight() {
    this.loadingOn();
    setTimeout(() => this.loadingOff(),2200);
  }

  loadingHeavy() {
    this.loadingOn();
    setTimeout(() => this.loadingOff(), 3200);
  }

}
