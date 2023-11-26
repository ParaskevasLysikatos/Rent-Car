import { Component, ViewChild } from '@angular/core';
import { CreateFormComponent } from 'src/app/create-form/create-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { QuotesFormComponent } from '../quotes-form/quotes-form.component';
import { IQuotes } from '../quotes.interface';
import { QuotesService } from '../quotes.service';
import moment from 'moment';
import { catchError } from 'rxjs/internal/operators/catchError';
import { filter } from 'rxjs/internal/operators/filter';

@Component({
  selector: 'app-create-quotes',
  templateUrl: './create-quotes.component.html',
  styleUrls: ['./create-quotes.component.scss'],
  providers: [{provide: ApiService, useClass: QuotesService}]
})
export class CreateQuotesComponent extends CreateFormComponent<IQuotes> {
  @ViewChild(QuotesFormComponent, {static: true}) formComponent!: QuotesFormComponent;
  currentDate = moment().format('YYYY-MM-DD HH:mm');
  nextDate = moment().add(1, 'days').format('YYYY-MM-DD HH:mm');
  nextTenDays = moment().add(10, 'days').format('YYYY-MM-DD HH:mm');

  quoteCreateOneTime: boolean = true;
  currUserStationO:boolean = false;
  currUserStationI: boolean = false;
  compPrefComplete:boolean = false;

  ngOnInit(): void {
    super.ngOnInit();
  }

  submitted = (res) => {
   // console.log(res);'
    // if (this.formComponent.form.untouched) {
    //   this.formComponent.ShowCheckbox();
    // }
  }



  ngAfterViewInit() {
    // this.formComponent.loadSrv.loadingOn();
    this.formComponent.$itemsLoad.pipe(filter(value => value)).subscribe(() => {// itemsLoad must come true to begin

      //Γενικές Πληροφορίες
      this.formComponent.form.controls.created_at.patchValue(this.currentDate);
      this.formComponent.form.controls.valid_date.patchValue(this.nextTenDays);//10 days default
      let currentUser = JSON.parse(localStorage.getItem('loggedUser'));
      this.formComponent.form.controls.user_id.patchValue(currentUser.id);

      //Πληροφορίες Παράδοσης  //Πληροφορίες Παραλαβής
      this.formComponent.form.controls.checkout_station_id.patchValue(currentUser.station_id);
      this.formComponent.checkout_station_id = currentUser.station_id;
      this.formComponent.form.controls.checkin_station_id.patchValue(currentUser.station_id);
      this.formComponent.checkin_station_id = currentUser.station_id;
      this.formComponent.stationSrv.edit(currentUser.station_id).subscribe(res => {
        this.formComponent.form.controls.checkout_place.patchValue({ //choose first filtered place
          id: res?.places[0]?.id,
          name: res?.places[0]?.profiles?.el?.title
        });
        this.formComponent.form.controls.checkin_place.patchValue({ //choose first filtered place
          id: res?.places[0]?.id,
          name: res?.places[0]?.profiles?.el?.title
        });
        this.currUserStationO = true;
        this.currUserStationI = true;
      });


      //company preferences
      this.formComponent.form.controls.source_id.patchValue(this.formComponent.companyPrefData.quote_source_id);
      this.formComponent.source_id = this.formComponent.companyPrefData.quote_source_id;
      this.formComponent.sourceEventInit(this.formComponent.companyPrefData.quote_source_id);
      this.formComponent.form.get('summary_charges.vat').patchValue(this.formComponent.companyPrefData.vat);//fpa
      this.compPrefComplete = true;
      //will come from η εταιρια μου


      //Πληροφορίες Παράδοσης datetime init
      this.formComponent.form.controls.checkout_datetime.patchValue(this.currentDate);
      this.formComponent.checkout_datetime = this.formComponent.form.controls.checkout_datetime.value;
      setTimeout(() => this.formComponent.checkOutDate.timepickerControl.patchValue(moment(this.currentDate).format('HH:mm')), 500);


      //Πληροφορίες Παραλαβής datetime init
      this.formComponent.form.controls.checkin_datetime.patchValue(this.nextDate)// next date
      this.formComponent.checkin_datetime = this.formComponent.form.controls.checkin_datetime.value;
      setTimeout(() => this.formComponent.checkInDate.timepickerControl.patchValue(moment(this.nextDate).format('HH:mm')), 500);


      this.formComponent.form.controls.duration.patchValue(1);

      // default Πληροφορίες διάρκειας and summary charges
      this.formComponent.form.controls.extension_rate.patchValue(0);
      this.formComponent.form.get('summary_charges.rate').patchValue(0);
      this.formComponent.form.get('summary_charges.distance').patchValue(0);
      this.formComponent.form.get('summary_charges.distance_rate').patchValue(0);
      this.formComponent.form.get('summary_charges.discount').patchValue(0);

      this.formComponent.form.get('summary_charges.rental_fee').patchValue(0);
      this.formComponent.form.get('summary_charges.transport_fee').patchValue(0);
      this.formComponent.form.get('summary_charges.insurance_fee').patchValue(0);
      this.formComponent.form.get('summary_charges.options_fee').patchValue(0);
      this.formComponent.form.get('summary_charges.fuel_fee').patchValue(0);
      this.formComponent.form.get('summary_charges.subcharges_fee').patchValue(0);
      this.formComponent.form.get('summary_charges.vat_fee').patchValue(0);
      this.formComponent.form.get('summary_charges.total').patchValue(0);
      this.formComponent.form.get('summary_charges.total_net').patchValue(0);

      //extra initialized values for quote
      this.formComponent.form.get('summary_charges.voucher').patchValue(0);

      this.formComponent.form.get('options').patchValue([]);

    });

   // this.formComponent.printQuoteSrv.afterDataLoadSubject.next(true);// because is used in formComponent
  }

  ngAfterViewChecked() {
    let sourceCpl = this.formComponent.sourceComplete;
    let optionsCpl = this.formComponent.optionsItemsComplete;

    // wait until all after-view-init methods complete and run one time this if to activate fom component calcs
    if (this.quoteCreateOneTime && sourceCpl && this.currUserStationO
      && this.currUserStationI && this.compPrefComplete && optionsCpl) {
      this.formComponent.payersCollection();
      console.log(...this.formComponent.items.value);
      this.formComponent.printQuoteSrv.afterDataLoadSubject.next(true);
      this.quoteCreateOneTime = false;
      console.log('quote create afterViewInit activated');
    }

  }

}
