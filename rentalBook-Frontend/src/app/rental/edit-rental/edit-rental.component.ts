import { Component, Injector, ViewChild } from '@angular/core';
import { FormArray } from '@angular/forms';
import moment from 'moment';
import { delay } from 'rxjs/internal/operators/delay';
import { filter } from 'rxjs/internal/operators/filter';
import { first } from 'rxjs/internal/operators/first';
import { last } from 'rxjs/internal/operators/last';
import { take } from 'rxjs/internal/operators/take';
import { IBookingItem } from 'src/app/booking-item/booking-item.interface';
import { EditFormComponent } from 'src/app/edit-form/edit-form.component';
import { InvoicePrintComponent } from 'src/app/invoices/invoice-print/invoice-print.component';
import { MyLoadingService } from 'src/app/my-loading/my-loading.service';
import { ISummaryCharges } from 'src/app/summary-charges/summary-charges.interface';
import { ApiService } from 'src/app/_services/api-service.service';
import { PrintRentalComponent } from '../print-rental/print-rental.component';
import { RentalFormComponent } from '../rental-form/rental-form.component';
import { RentalSignatureComponent } from '../rental-signature/rental-signature.component';
import { IRental } from '../rental.interface';
import { RentalService } from '../rental.service';

@Component({
  selector: 'app-edit-rental',
  templateUrl: './edit-rental.component.html',
  styleUrls: ['./edit-rental.component.scss'],
  providers: [{provide: ApiService, useClass: RentalService}]
})
export class EditRentalComponent extends EditFormComponent<IRental> {
  @ViewChild(RentalFormComponent, {static: true}) formComponent!: RentalFormComponent;

  statusHeader!: string;
  rentalEditOneTime: boolean = true;
  resR!: IRental;

  statusHeaderHandler($event: any) {
    this.statusHeader = $event;
    if (this.statusHeader=='active'){
      this.statusHeader='Ενεργή'
    }
    else if (this.statusHeader == 'closed') {
      this.statusHeader = 'Κλειστό'
    }
    else if (this.statusHeader == 'cancelled') {
      this.statusHeader ='Ακυρωμένη'
    }
    else if (this.statusHeader == 'check-in') {
        this.statusHeader='Check-in'
    }
    else {//pre-check
      this.statusHeader='Pre Check'
    }
  }


   afterDataLoad(res:IRental) {
    // this.formComponent.loadSrv.loadingOn();
     if (this.formComponent.customUrl != 'create' && this.rentalEditOneTime==false){// edit mode and saved again
       this.formComponent.summary_options = this.orderSummaryCharges(res.summary_charges);
       this.rentalEditOneTime = true;// after save again to run again
     }

    this.formComponent.$itemsLoad.pipe(filter(value => value)).subscribe(() => {// itemsLoad must come true to begin
       this.resR = res;
       this.formComponent.form.controls.checkout_datetime.patchValue(moment(res.checkout_datetime).format('YYYY-MM-DD HH:mm'));
       this.formComponent.checkout_datetime = moment(res.checkout_datetime).format('YYYY-MM-DD HH:mm');
       this.formComponent.checkOutDate.timepickerControl.patchValue(moment(res.checkout_datetime).format('HH:mm'));

       this.formComponent.form.controls.checkin_datetime.patchValue(moment(res.checkin_datetime).format('YYYY-MM-DD HH:mm'));
       this.formComponent.checkin_datetime = moment(res.checkin_datetime).format('YYYY-MM-DD HH:mm');
       this.formComponent.checkInDate.timepickerControl.patchValue(moment(res.checkin_datetime).format('HH:mm'));

       this.formComponent.mayExtend();

       this.formComponent.summary_options = this.orderSummaryCharges(res.summary_charges);
       //this.formComponent.form.controls.summary_charges.patchValue(this.formComponent.summary_options);

       //-- calc initialy from db if plus day is activated
       let dateOut = new Date(res.checkout_datetime);
       let dateIn = new Date(res.checkin_datetime);
       let timeDiff = dateIn.getTime() - dateOut.getTime();
       let daysDiff = timeDiff / (1000 * 3600 * 24);
       this.formComponent.durationInitial = Number(res.duration);
       if (Number(res.duration) < daysDiff) {
         this.formComponent.plusDayInitial = true;
         // this.formComponent.calcPlusday();
       } else {
         this.formComponent.plusDayInitial = false;
         // this.formComponent.calcPlusday();
       }
       //-----
       this.formComponent.tag_Ref.tags = res.tags;
       this.formComponent.sequence_number = res.sequence_number;
       this.formComponent.modification_number = res.modification_number;
       if (this.formComponent.modification_number == '0') {
         this.formComponent.modification_number = '';
       }
       this.formComponent.exchanges = res.exchanges;//payments and invoices are in form
       this.formComponent.overall_travelled_km = res.getKmDrivenAttribute;

       this.formComponent.driver_id = res.driver_id;
       if (res.driver_id != null) {
         this.formComponent.driverEventInit(res.driver_id);
       } else {
         this.formComponent.driverComplete = true;
       }

       this.formComponent.company_id = res.company_id;
       if(res.company_id){
         this.formComponent.companyEventInit(res.company_id);
       }
       else{
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
         // this.formComponent.subAccountEvent(); //must be initiated after agent event!!
       } else {
         this.formComponent.agentComplete = true;
       }
       //needs to initiate after agent event the source event

       this.formComponent.source_id = res.source_id;
       if (res.source_id != null) {
         this.formComponent.sourceEventInit(res.source_id);
       } else {
         this.formComponent.sourceComplete;
       }

       // before group event, cause needs them
       this.formComponent.vehicle_id = res.vehicle_id;
       this.formComponent.vehicleData = res.vehicle;

       this.formComponent.type_id = res.type_id;
       if (res.type_id != null) {
         this.formComponent.groupEventInit(res.type_id);
       } else {
         this.formComponent.groupComplete = true;
       }

       //this.formComponent.vehicleEvent(); no need ,will cancel group event

       this.formComponent.selectedStatus.emit(this.formComponent.form.controls.status.value);//header of rental
       this.formComponent.form.controls.convert.patchValue(false);//in case after convert save
       console.log('ccc');
       //this.formComponent.printRentalSrv.afterDataLoadSubject.next(true);
     });
  }

  rentalHeader(res: IRental): string {
    let rHeader = res.sequence_number;
    if (res.modification_number != '0') {
      rHeader += ' (' + res.modification_number + ') -';
    }
    rHeader += ' '+this.statusHeader;
    return rHeader;
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
    if (this.rentalEditOneTime && sourceCpl && driverCpl && stationInCpl
      && stationOutCpl && agentCpl && groupCpl && optionsCpl && companyCpl) {
      this.formComponent.payersCollection();
      this.formComponent.saveItemsInit('rental_charges', 'summary_charges.rental_fee');
      console.log(this.formComponent.payers);
      // title of breadcrumb
      this.formComponent.breadcrumbsSrv.TitleSubject.next(this.rentalHeader(this.resR));
      //methods has change their user values to defaults
      this.formComponent.printRentalSrv.afterDataLoadSubject.next(true);
      this.rentalEditOneTime = false;
      console.log('rental edit afterDL activated');
        //first time print
      if (this.formComponent.printRentalSrv.createFirstTime.getValue()) {//cause in create is too early
        // console.log('submitted rental edit');

          // this.formComponent.matDialog.open(PrintRentalComponent, {
          //   width: '80%',
          //   height: '80%',
          //   data: this.formComponent.form.controls.id.value,
          //   autoFocus: false
          // });

        this.formComponent.formDialogSrv.showDialog(RentalSignatureComponent, { obj: this.formComponent.form.value }, false)
          .subscribe(() => {

            this.formComponent.matDialog.open(PrintRentalComponent, {
            width: '80%',
            height: '80%',
            data: this.formComponent.form.controls.id.value,
            autoFocus: false
             });
          });


        this.formComponent.printRentalSrv.createFirstTime.next(false);
      }

      // after save show invoice print if close status
      if (this.formComponent.afterClosedStatusSave) {
        this.formComponent.afterClosedStatusSave = false;
        for (let i = 1; i < this.resR.invoices.length; i++) {//neglect first one
          this.formComponent.printCheckboxSrv.arrayPrints.push({ component: InvoicePrintComponent, data: this.resR.invoices[i-1].id })//if its more than one
        }
          this.formComponent.matDialog.open(InvoicePrintComponent, {
            width: '80%',
            height: '80%',
            data: this.formComponent.form.controls.invoices.value[0].id,//first one is here(neglected one)
            autoFocus: false
          });
      }

    }

  }



}
