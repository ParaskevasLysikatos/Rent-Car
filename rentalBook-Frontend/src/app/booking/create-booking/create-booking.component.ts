import { Component, ViewChild } from '@angular/core';
import { CreateFormComponent } from 'src/app/create-form/create-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { BookingFormComponent } from '../booking-form/booking-form.component';
import { IBooking } from '../booking.interface';
import { BookingService } from '../booking.service';
import moment from 'moment';
import { filter } from 'rxjs';
import { IBookingItem } from 'src/app/booking-item/booking-item.interface';


@Component({
  selector: 'app-create-booking',
  templateUrl: './create-booking.component.html',
  styleUrls: ['./create-booking.component.scss'],
  providers: [{ provide: ApiService, useClass: BookingService }]
})
export class CreateBookingComponent extends CreateFormComponent<IBooking> {
  @ViewChild(BookingFormComponent, { static: true }) formComponent!: BookingFormComponent;
  currentDate = moment().format('YYYY-MM-DD HH:mm');
  nextDate = moment().add(1, 'days').format('YYYY-MM-DD HH:mm');

  bookingCreateOneTime: boolean = true;
  currUserStationO: boolean = false;
  currUserStationI: boolean = false;
  compPrefComplete: boolean = false;

  ngOnInit(): void {
    super.ngOnInit();
  }

  submitted = (res) => {
    // if (this.formComponent.form.untouched) {
    //   this.formComponent.ShowCheckbox();
    // }
  }


  ngAfterViewInit() {
    //this.formComponent.loadSrv.loadingOn();
    if (this.formComponent.createBookingSrv.createBookSubject.getValue()) {//comes from quote
//summary options must wait options in form init to be completed first!!!
      this.formComponent.$itemsLoad.pipe(filter(value => value)).subscribe(() => {// itemsLoad must come true to begin
        // all the form from quote
        this.formComponent.form.patchValue(this.formComponent.createBookingSrv.createBookSubject.getValue());//give booking form values
        console.log(this.formComponent.createBookingSrv.createBookSubject.getValue());

        //duration
        this.formComponent.duration = +this.formComponent.createBookingSrv.createBookSubject.getValue().duration;

        //tag
        this.formComponent.tag_Ref.tags.push(...this.formComponent.form.controls.tags.value);

        //summary charges and init options
        console.log(this.formComponent.createBookingSrv.summaryChargesItemsSub.getValue());
        this.formComponent.summary_options = this.formComponent.createBookingSrv.summaryChargesSub.getValue();

      //payers
      this.formComponent.payers = this.formComponent.createBookingSrv.payersSub.getValue();

      // //check-in-out datetime
      this.formComponent.checkout_datetime = this.formComponent.form.controls.checkout_datetime.value;
      this.formComponent.checkOutDate.timepickerControl.patchValue(moment(this.formComponent.form.controls.checkout_datetime.value).format('HH:mm'));
      this.formComponent.checkin_datetime = this.formComponent.form.controls.checkin_datetime.value;
      this.formComponent.checkInDate.timepickerControl.patchValue(moment(this.formComponent.form.controls.checkin_datetime.value).format('HH:mm'));
      this.formComponent.changeDate();

      this.formComponent.form.controls.status.patchValue(null);//no value on create booking

      //station
      this.formComponent.checkout_station_id = this.formComponent.createBookingSrv.stationOutSub.getValue();
      if (this.formComponent.createBookingSrv.stationOutSub.getValue() != null) {
        this.formComponent.stationOutEventInit(this.formComponent.createBookingSrv.stationOutSub.getValue());
      } else {
        this.formComponent.stationOutComplete = true;
      }

      this.formComponent.checkin_station_id = this.formComponent.createBookingSrv.stationInSub.getValue();
      if (this.formComponent.createBookingSrv.stationInSub.getValue() != null) {
        this.formComponent.stationInEventInit(this.formComponent.createBookingSrv.stationInSub.getValue());
      } else {
        this.formComponent.stationInComplete = true;
      }


      this.formComponent.company_id = this.formComponent.createBookingSrv.createBookSubject.getValue().company_id;
        if (this.formComponent.createBookingSrv.createBookSubject.getValue().company_id) {
          this.formComponent.companyEventInit(this.formComponent.createBookingSrv.createBookSubject.getValue().company_id);
        } else {
          this.formComponent.companyComplete = true;
        }

      //agent
      this.formComponent.agent_id = this.formComponent.createBookingSrv.agentSub.getValue();
      //sub account
      this.formComponent.sub_account_id = this.formComponent.createBookingSrv.createBookSubject.getValue().sub_account_id;
      this.formComponent.sub_account_type = this.formComponent.createBookingSrv.createBookSubject.getValue().sub_account_type;

      if (this.formComponent.createBookingSrv.agentSub.getValue() != null) {
        this.formComponent.agentEventInit(this.formComponent.createBookingSrv.agentSub.getValue());
        //this.formComponent.subAccountEvent();  must be initiated after agent event!!
      } else {
        this.formComponent.agentComplete = true;
      }

      //agent must be initialized before source

      //source
      this.formComponent.source_id = this.formComponent.createBookingSrv.sourceSub.getValue();
      if (this.formComponent.createBookingSrv.sourceSub.getValue() != null) {
        this.formComponent.sourceEventInit(this.formComponent.createBookingSrv.sourceSub.getValue());
      } else {
        this.formComponent.sourceComplete = true;
      }


      //group , not vehicle from quote
      this.formComponent.type_id = this.formComponent.createBookingSrv.typeSub.getValue();
      if (this.formComponent.createBookingSrv.typeSub.getValue() != null) {
        this.formComponent.groupEventInit(this.formComponent.createBookingSrv.typeSub.getValue());
      } else {
        this.formComponent.groupComplete = true;
      }

      //driver
      this.formComponent.form.controls.customer_id.patchValue(this.formComponent.createBookingSrv.driverSub.getValue());
      this.formComponent.customer_id = this.formComponent.createBookingSrv.driverSub.getValue();// also customer text must
      this.formComponent.driverEvent();

      //this.formComponent.vehicleEvent(); no need ,will cancel group event

      this.formComponent.form.controls.id.patchValue(null);//cause is new booking, not the quote id
      this.formComponent.form.controls.payments.patchValue([]);// needed backend asks
      this.formComponent.seeValuesConsole();
      this.formComponent.form.controls.convert.patchValue(true);

      });//end of subscribe

      // this.formComponent.printBookingSrv.afterDataLoadSubject.next(true);
    }
    else { //classic create booking

      this.formComponent.$itemsLoad.pipe(filter(value => value)).subscribe(() => {// itemsLoad must come true to begin
        //Γενικές Πληροφορίες
        this.formComponent.form.controls.created_at.patchValue(this.currentDate);
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
        this.formComponent.form.controls.source_id.patchValue(this.formComponent.companyPrefData.booking_source_id);
        this.formComponent.source_id = this.formComponent.companyPrefData.booking_source_id;
        this.formComponent.sourceEventInit(this.formComponent.companyPrefData.booking_source_id);
        this.formComponent.form.get('summary_charges.vat').patchValue(this.formComponent.companyPrefData.vat);//fpa
        this.compPrefComplete = true;
        //will come from η εταιρια μου

        //Πληροφορίες Παράδοσης datetime init
        this.formComponent.form.controls.checkout_datetime.patchValue(this.currentDate);
        this.formComponent.checkout_datetime = this.formComponent.form.controls.checkout_datetime.value;
        this.formComponent.checkOutDate.timepickerControl.patchValue(moment(this.currentDate).format('HH:mm'));

        //Πληροφορίες Παραλαβής  datetime init
        this.formComponent.form.controls.checkin_datetime.patchValue(this.nextDate)// next date
        this.formComponent.checkin_datetime = this.formComponent.form.controls.checkin_datetime.value;
        this.formComponent.checkInDate.timepickerControl.patchValue(moment(this.nextDate).format('HH:mm'));

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

        //extra initialized values for booking
        this.formComponent.form.get('summary_charges.voucher').patchValue(0);
        this.formComponent.form.get('program_id').patchValue(1);

        this.formComponent.form.get('payments').patchValue([]);
        this.formComponent.form.get('options').patchValue([]);
      });
      // this.formComponent.printBookingSrv.afterDataLoadSubject.next(true);// because is used in formComponent
    }
  }


  ngAfterViewChecked() {
    let sourceCpl = this.formComponent.sourceComplete;
    //when comes from quote --///
    let driverCpl = this.formComponent.driverComplete;
    let stationOutCpl = this.formComponent.stationOutComplete;
    let stationInCpl = this.formComponent.stationInComplete;
    let agentCpl = this.formComponent.agentComplete;
    let groupCpl = this.formComponent.groupComplete;
    let optionsCpl = this.formComponent.optionsItemsComplete;
    let companyCpl = this.formComponent.companyComplete;
    //-----//

    // wait until all  after-view-init methods complete and run one time this if to activate fom component calcs
    if (this.bookingCreateOneTime && sourceCpl && this.currUserStationO
      && this.currUserStationI && this.compPrefComplete) {
      this.formComponent.payersCollection();//here needed, in convert I pass them
      this.formComponent.printBookingSrv.afterDataLoadSubject.next(true);
      this.bookingCreateOneTime = false;
      console.log('booking create afterViewInit activated');
    }
    // come from quote convert
    else if (this.bookingCreateOneTime && sourceCpl && driverCpl
      && stationInCpl && stationOutCpl && agentCpl && groupCpl && optionsCpl && companyCpl) {
      //methods has change their user values to defaults
      this.formComponent.saveItems('rental_charges', 'summary_charges.rental_fee');
      this.formComponent.printBookingSrv.afterDataLoadSubject.next(true);
      this.bookingCreateOneTime = false;
      console.log('booking create from quote  activated');
    }

  }

  nullifyOptionsId(array: IBookingItem[]): IBookingItem[] {
    array.forEach((item) => item.id = '');
    return array;
  }


}
