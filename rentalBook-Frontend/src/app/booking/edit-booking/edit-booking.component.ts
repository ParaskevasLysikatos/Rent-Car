import { Component, ViewChild } from '@angular/core';
import moment from 'moment';
import { delay } from 'rxjs/internal/operators/delay';
import { filter } from 'rxjs/internal/operators/filter';
import { first } from 'rxjs/internal/operators/first';
import { take } from 'rxjs/internal/operators/take';
import { IBookingItem } from 'src/app/booking-item/booking-item.interface';
import { EditFormComponent } from 'src/app/edit-form/edit-form.component';
import { ISummaryCharges } from 'src/app/summary-charges/summary-charges.interface';
import { ApiService } from 'src/app/_services/api-service.service';
import { BookingFormComponent } from '../booking-form/booking-form.component';
import { IBooking } from '../booking.interface';
import { BookingService } from '../booking.service';
import { PrintBookingComponent } from '../print-booking/print-booking.component';

@Component({
  selector: 'app-edit-booking',
  templateUrl: './edit-booking.component.html',
  styleUrls: ['./edit-booking.component.scss'],
  providers: [{provide: ApiService, useClass: BookingService}]
})
export class EditBookingComponent extends EditFormComponent<IBooking> {
  @ViewChild(BookingFormComponent, {static: true}) formComponent!: BookingFormComponent;

  statusHeader!: string;
  bookingEditOneTime: boolean = true;
  resB!: IBooking;

  statusHeaderHandler($event: any) {
    this.statusHeader = $event;
    if (this.statusHeader == 'pending') {
      this.statusHeader = 'Αναμονή'
      this.formComponent.cancel_reason = '';
    }
    else if (this.statusHeader == 'rental') {
      this.statusHeader = 'Ενοικίαση'
    }
    else if (this.statusHeader == 'cancelled') {
      this.statusHeader = 'Ακυρωμένη'
    }
    else if (this.statusHeader == 'sold') {
      this.statusHeader = 'Πώληση'
      this.formComponent.cancel_reason = '';
    }
    else {//nothing
      this.statusHeader = 'None'
    }
  }




  afterDataLoad(res: IBooking) {
  //  this.formComponent.loadSrv.loadingOn();
    if (this.formComponent.customUrl != 'create' && this.bookingEditOneTime == false) {// edit mode and saved again
      this.formComponent.summary_options = this.orderSummaryCharges(res.summary_charges);
      this.bookingEditOneTime = true;// after save again to run again
    }
    this.formComponent.$itemsLoad.pipe(filter(value => value)).subscribe(() => {// itemsLoad must come true to begin
      this.resB = res;
      this.formComponent.form.controls.checkout_datetime.patchValue(moment(res.checkout_datetime).format('YYYY-MM-DD HH:mm'));
      this.formComponent.checkout_datetime = moment(res.checkout_datetime).format('YYYY-MM-DD HH:mm');
      this.formComponent.checkOutDate.timepickerControl.patchValue(moment(res.checkout_datetime).format('HH:mm'));

      this.formComponent.form.controls.checkin_datetime.patchValue(moment(res.checkin_datetime).format('YYYY-MM-DD HH:mm'));
      this.formComponent.checkin_datetime = moment(res.checkin_datetime).format('YYYY-MM-DD HH:mm');
      this.formComponent.checkInDate.timepickerControl.patchValue(moment(res.checkin_datetime).format('HH:mm'));

      this.formComponent.mayExtend();

      this.formComponent.summary_options = this.orderSummaryCharges(res.summary_charges);
      // this.formComponent.form.controls.summary_charges.patchValue(this.formComponent.summary_options);


      //-- calc initialy from db if plus day is activated
      let dateOut = new Date(res.checkout_datetime);
      let dateIn = new Date(res.checkin_datetime);
      let timeDiff = dateIn.getTime() - dateOut.getTime();
      let daysDiff = timeDiff / (1000 * 3600 * 24);
      this.formComponent.durationInitial = Number(res.duration);
      if (Number(res.duration) < daysDiff) {
        this.formComponent.plusDayInitial = true;
        //this.formComponent.calcPlusday();
      } else {
        this.formComponent.plusDayInitial = false;
        // this.formComponent.calcPlusday();
      }
      //-----
      this.formComponent.tag_Ref.tags = res.tags;
      this.formComponent.rental = res.rental;

      this.formComponent.sequence_number = res.sequence_number;
      this.formComponent.modification_number = res.modification_number;
      if (this.formComponent.modification_number == '0') {
        this.formComponent.modification_number = '';
      }
      this.formComponent.cancelSrv.getOne(+res.cancel_reason_id).subscribe(res => this.formComponent.cancel_reason = res?.title);

      this.formComponent.customer_id = res.customer_id;//οδηγός
      this.formComponent.driverEvent();

      this.formComponent.company_id = res.company_id;
      if (res.company_id) {
        this.formComponent.companyEventInit(res.company_id);
      }
      else {
        this.formComponent.companyComplete = true;
      }

      this.formComponent.checkout_station_id = res.checkout_station_id;
      if (res.checkout_station_id != null) {
        this.formComponent.stationOutEventInit(res.checkout_station_id);
      } else {
        this.formComponent.stationOutComplete = true;
      }

      this.formComponent.checkin_station_id = res.checkin_station_id;
      if (res.checkin_station_id != null) {
        this.formComponent.stationInEventInit(res.checkin_station_id);
      } else {
        this.formComponent.stationInComplete = true;
      }

      this.formComponent.agent_id = res.agent_id;
      this.formComponent.sub_account_id = res.sub_account_id;
      this.formComponent.sub_account_type = res.sub_account_type;
      if (res.agent_id != null) {
        this.formComponent.agentEventInit(res.agent_id);
        // this.formComponent.subAccountEvent();  must be initiated after agent event!!
      } else {
        this.formComponent.agentComplete = true;
      }

      //agent must be initialize before source

      this.formComponent.source_id = res.source_id;
      if (res.source_id != null) {
        this.formComponent.sourceEventInit(res.source_id);
      } else {
        this.formComponent.sourceComplete = true;
      }

      // must be initialized before group event !!!
      this.formComponent.vehicle_id = res.vehicle_id;
      this.formComponent.vehicleData = res.vehicle;

      this.formComponent.type_id = res.type_id;
      if (res.type_id != null) {
        this.formComponent.groupEventInit(res.type_id);
      } else {
        this.formComponent.groupComplete = true;
      }

      //this.formComponent.vehicleEvent(); no need ,will cancel group event
      this.formComponent.form.controls.convert.patchValue(false);//in case after convert save
      this.formComponent.selectedStatus.emit(this.formComponent.form.controls.status.value);//header of rental
      console.log('ccc');
      // this.formComponent.printBookingSrv.afterDataLoadSubject.next(true) ;
    });
  }



  bookingHeader(res: IBooking): string {
    let bHeader = '- ' + res.sequence_number;
    if (res.modification_number != '0') {
      bHeader += ' (' + res.modification_number + ') -';
    }
    bHeader += ' '+ this.statusHeader;
    if (res.cancel_reason_id) {
      bHeader += ' (' + this.formComponent.cancel_reason + ')'
    }
    return bHeader;
  }


  sorting(array: IBookingItem[]): IBookingItem[] {
    return array.sort((a, b) => a.option.order - b.option.order || +a.option.id - +b.option.id);
  }



  orderSummaryCharges(summary: ISummaryCharges): ISummaryCharges {
    let summaryItemsSorted: IBookingItem[] = this.sorting(summary.items);
    summary.items = summaryItemsSorted;
    return summary;
  }


  ngAfterViewChecked() {
    let sourceCpl = this.formComponent.sourceComplete;
    let driverCpl = this.formComponent.driverComplete;
    let stationOutCpl = this.formComponent.stationOutComplete;
    let stationInCpl = this.formComponent.stationInComplete;
    let agentCpl = this.formComponent.agentComplete;
    let groupCpl = this.formComponent.groupComplete;
    let optionsCpl = this.formComponent.optionsItemsComplete;
    let companyCpl = this.formComponent.companyComplete;

      // wait until all after data load methods complete an run one time this if to activate fom component calcs
    if (this.bookingEditOneTime && sourceCpl && driverCpl && stationInCpl
      && stationOutCpl && agentCpl && groupCpl && optionsCpl && companyCpl) {
      this.formComponent.payersCollection();
      this.formComponent.saveItemsInit('rental_charges', 'summary_charges.rental_fee');
      // title of breadcrumb
      this.formComponent.breadcrumbsSrv.TitleSubject.next(this.bookingHeader(this.resB));
      //methods has change their user values to defaults
      this.formComponent.printBookingSrv.afterDataLoadSubject.next(true);
      this.bookingEditOneTime = false;
      console.log('booking edit afterDL activated');
      //first time print
      if (this.formComponent.printBookingSrv.createFirstTime.getValue()) {//cause in create is too early
        // console.log('submitted booking edit');
          this.formComponent.matDialog.open(PrintBookingComponent, {
            width: '80%',
            height: '80%',
            data: this.formComponent.form.controls.id.value,
            autoFocus: false
          });
        this.formComponent.printBookingSrv.createFirstTime.next(false);
      }
    }

  }



}
