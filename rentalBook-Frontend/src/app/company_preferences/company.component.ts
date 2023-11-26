import { Component, ElementRef, HostListener, Injector, OnInit, ViewChild } from '@angular/core';
import { Validators } from '@angular/forms';
import { catchError, Observable, throwError } from 'rxjs';
import { AbstractFormComponent } from '../abstract-form/abstract-form.component';
import { IBookingSource } from '../booking-source/booking-source.interface';
import { BookingSourceService } from '../booking-source/booking-source.service';

import { CompanyService } from '../company/company.service';
import { ConfirmationDialogService } from '../confirmation-dialog/confirmation-dialog.service';
import { IStation } from '../stations/station.interface';
import { StationService } from '../stations/station.service';
import { AbstractAutocompleteSelectorComponent } from '../_selectors/abstract-autocomplete-selector/abstract-autocomplete-selector.component';
import { AbstractSelectorComponent } from '../_selectors/abstract-selector/abstract-selector.component';
import { SelectorService } from '../_selectors/selector/selector.service';
import { ApiService } from '../_services/api-service.service';
import { NotificationService } from '../_services/notification.service';
import { CompanyPreferencesService } from './company.service';

@Component({
  selector: 'app-company-preferences',
  templateUrl: './company.component.html',
  styleUrls: ['./company.component.scss'],
 providers: [{ provide: ApiService, useClass: CompanyService}]
})
export class CompanyPreferencesComponent extends AbstractFormComponent implements OnInit {
  form = this.fb.group({
    id:[],
    name: [],
    title: [],
    job: [],
    afm: [],
    doi: [],
    phone: [],
    email: [],
    website: [],
    mite_number: [],
    station_id: [],
    place: [],
    quote_source: [],
    quote_source_id: [],
    booking_source: [],
    booking_source_id: [],
    rental_source: [],
    rental_source_id: [],
    checkin_free_minutes: [],
    vat: [],
    timezone: [],
    quote_prefix: [],
    booking_prefix: [],
    rental_prefix: [],
    invoice_prefix: [],
    receipt_prefix: [],
    payment_prefix: [],
    pre_auth_prefix: [],
    refund_prefix: [],
    refund_pre_auth_prefix: [],
    quote_available_days: [],
    show_rental_charges: [],
    rental_rate_terms: [],
    rental_vehicle_condition: [],
    rental_gdpr: [],
    invoice_first_box: [],
    invoice_second_box: []
  });

  protected notificationSrv: NotificationService;
  @ViewChild('sourceQ', { static: false }) sourceQ_id_Ref: AbstractSelectorComponent<any>;
  @ViewChild('sourceB', { static: false }) sourceB_id_Ref: AbstractSelectorComponent<any>;
  @ViewChild('sourceR', { static: false }) sourceR_id_Ref: AbstractSelectorComponent<any>;

  @ViewChild('station', { static: false }) station_id_Ref: AbstractSelectorComponent<any>;

  constructor(protected injector: Injector, private cmpSrv: CompanyPreferencesService,
    public stationSrv: StationService<IStation>, protected selectorSrv: SelectorService,
    private dialogSrv: ConfirmationDialogService, public sourceSrv: BookingSourceService<IBookingSource>) {
      super(injector);
    this.notificationSrv = injector.get(NotificationService);
      }

  ngOnInit(): void {
    this.spinnerSrv.showSpinner(this.elementRef);
    this.sourceSrv.get({}, undefined, -1).subscribe(res => {
      this.sourceQ_id_Ref.selector.data = res.data;
      this.sourceB_id_Ref.selector.data = res.data;
      this.sourceR_id_Ref.selector.data = res.data;
    });

    this.stationSrv.get({}, undefined, -1).subscribe(res => {
      this.station_id_Ref.selector.data = res.data;
    });

    this.cmpSrv.edit().subscribe(res => {
      this.form.patchValue(res);
      this.spinnerSrv.hideSpinner();
    });
  }

  // onSubmit(): void {
  //   console.log('a');
  //   if (!this.form.errors) {
  //     this.cmpSrv.update(this.form.value).subscribe((res) => {
  //       this.notificationSrv.showSuccessNotification('Επιτυχής αποθήκευση');
  //     },(err => {
  //       this.notificationSrv.showErrorNotification(err.message);
  //       throw err;
  //     })
  //     );
  //   }
  // }

  canDeactivate(): Observable<boolean> | Promise<boolean> | boolean {
    if (this.form.dirty) {
      return this.dialogSrv.showDialog('Έχετε μη αποθηκευμένες αλλαγές. Είστε σίγουροι ότι θέλετε να φύγετε;');
    }
    return true;
  }


  //---------station auto change place---------------//

  station_Data: any;
  station_id: string;
  includePlaces = [];
  @ViewChild('place', { static: true }) place_id_Ref: AbstractAutocompleteSelectorComponent<any>;

  stationEvent() {
    this.selectorSrv.searchStation.subscribe(res => {
      this.station_Data = res;
      this.station_id = res.id;
      this.includePlaces = []//clear
      this.place_id_Ref.selector.options = [];
      res.places.forEach((item) => {
        this.includePlaces.push(item.id);
        this.place_id_Ref.selector.options.push(item);
        //filter places
      });
      //console.log(res.places);
      console.log(this.includePlaces);
      this.form.controls.place.patchValue({ //choose first filtered place
        id: this.station_Data?.places[0]?.id,
        name: this.station_Data?.places[0]?.profiles?.el?.title
      });
    });
  }

  @HostListener('document:click', ['$event'])
  EventClick(event: Event) {
    if (this.station_id != this.form.controls.station_id.value) {
      console.log('some click s');
      //  console.log(this.checkout_station_id);
      //  console.log(this.form.controls.checkout_station_id.value + ' new' + this.checkout_station_id);
      this.stationEvent();
    }
  }

  @HostListener('document:change', ['$event'])
  EventChange(event: Event) {
    if (this.station_id != this.form.controls.station_id.value) {
      console.log('some change s');
      //  console.log(this.checkout_station_id);
      //  console.log(this.form.controls.checkout_station_id.value + ' new' + this.checkout_station_id);
      this.stationEvent();
    }
  }

  //------------//


}
