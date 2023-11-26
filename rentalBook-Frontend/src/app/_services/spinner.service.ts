import { OverlayConfig, OverlayRef } from '@angular/cdk/overlay';
import { ComponentPortal } from '@angular/cdk/portal';
import { ElementRef, Injectable } from '@angular/core';
import { MatSpinner } from '@angular/material/progress-spinner';
import { Observable, Subject } from 'rxjs';
import { DynamicOverlayService } from './dynamic-overlay.service';

@Injectable({
  providedIn: 'root'
})
export class SpinnerService {
  show: Subject<boolean> = new Subject();
  show$ = this.show.asObservable();
  overlayRef!: OverlayRef;
  hasSelectors = false;
  selectorsLoaded = false;
  dataLoaded = false;

  constructor(public overlay: DynamicOverlayService) { }

  change(show: boolean): void {
    this.show.next(show);
  }

  onChange(): Observable<boolean> {
    return this.show$;
  }

  showSpinner(viewContainerRef: ElementRef): void {
    const config = new OverlayConfig();

   this.overlayRef = this.overlay.createWithDefaultConfig(viewContainerRef.nativeElement);

   this.overlayRef.attach(new ComponentPortal(MatSpinner));
    this.overlayRef;
  }

  setUpSelectors(): void {
    this.hasSelectors = true;
  }

  // selectorsLoad(): void {
  //   this.selectorsLoaded = true;
  //   if (this.dataLoaded) {

  //   }
  // }

  dataLoad(): void {
    this.dataLoaded = true;
    if (!this.hasSelectors) {
      this.hideSpinner();
    }
  }

  hideSpinner(overlayRef?: OverlayRef): void {
    this.overlayRef?.dispose();
    this.hasSelectors = false;
    this.selectorsLoaded = false;
    this.dataLoaded = false;
  }
}
