import { Component, ElementRef, ViewChild } from '@angular/core';
import moment from 'moment';
import { filter } from 'rxjs/internal/operators/filter';
import { Subject } from 'rxjs/internal/Subject';
import { IBookingItem } from 'src/app/booking-item/booking-item.interface';
import { EditFormComponent } from 'src/app/edit-form/edit-form.component';
import { ISummaryCharges } from 'src/app/summary-charges/summary-charges.interface';
import { ApiService } from 'src/app/_services/api-service.service';
import { PrintQuoteComponent } from '../print-quote/print-quote.component';
import { QuotesFormComponent } from '../quotes-form/quotes-form.component';
import { IQuotes } from '../quotes.interface';
import { QuotesService } from '../quotes.service';

@Component({
  selector: 'app-edit-quotes',
  templateUrl: './edit-quotes.component.html',
  styleUrls: ['./edit-quotes.component.scss'],
  providers: [{provide: ApiService, useClass: QuotesService}]
})
export class EditQuotesComponent extends EditFormComponent<IQuotes> {
  @ViewChild(QuotesFormComponent, {static: true}) formComponent!: QuotesFormComponent;


  statusHeader: string='';
  quoteEditOneTime: boolean=true;
  resQ!:IQuotes;

  statusHeaderHandler($event: any) {
    this.statusHeader = $event;
    if (this.statusHeader == 'booking') {
      this.statusHeader = 'Κράτηση'
    }
    else if (this.statusHeader == 'active') {
      this.statusHeader = 'Ενεργή'
      this.formComponent.cancel_reason = '';
    }
    else if (this.statusHeader == 'cancelled') {
      this.statusHeader = 'Ακυρωμένη'
    }
    else {//nothing
      this.statusHeader = 'None'
    }
  }



   afterDataLoad(res: IQuotes) {
    // this.formComponent.loadSrv.loadingOn();
     if (this.formComponent.customUrl != 'create' && this.quoteEditOneTime == false) {// edit mode and saved again
       this.formComponent.summary_options = this.orderSummaryCharges(res.summary_charges);
       this.quoteEditOneTime = true;// after save again to run again
     }
     this.formComponent.$itemsLoad.pipe(filter(value => value)).subscribe(() => {// itemsLoad must come true to begin
       this.resQ = res;
       this.formComponent.form.controls.valid_date.patchValue(moment(res.valid_date).format('YYYY-MM-DD HH:mm'));

       this.formComponent.form.controls.checkout_datetime.patchValue(moment(res.checkout_datetime).format('YYYY-MM-DD HH:mm'));
       this.formComponent.checkout_datetime = moment(res.checkout_datetime).format('YYYY-MM-DD HH:mm');
       setTimeout(() => this.formComponent.checkOutDate.timepickerControl.patchValue(moment(res.checkout_datetime).format('HH:mm')), 500);

       this.formComponent.form.controls.checkin_datetime.patchValue(moment(res.checkin_datetime).format('YYYY-MM-DD HH:mm'));
       this.formComponent.checkin_datetime = moment(res.checkin_datetime).format('YYYY-MM-DD HH:mm');
       setTimeout(() => this.formComponent.checkInDate.timepickerControl.patchValue(moment(res.checkin_datetime).format('HH:mm')), 500);

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
         //  this.formComponent.calcPlusday();
       }
       //-----
       this.formComponent.tag_Ref.tags = res.tags;
       this.formComponent.booking = res.booking;

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
         // this.formComponent.subAccountEvent(); must be initiated after agent event!!
       } else {
         this.formComponent.agentComplete = true;
       }
       //needs to initiate after agent event the source event
       this.formComponent.source_id = res.source_id;
       if (res.source_id != null) {
         this.formComponent.sourceEventInit(res.source_id);
       } else {
         this.formComponent.sourceComplete = true;
       }

       //group
       this.formComponent.type_id = res.type_id;
       //this.formComponent.groupEventInit(res.type_id); not needed

       this.formComponent.selectedStatus.emit(this.formComponent.form.controls.status.value);//header of rental

       console.log('ccc');
       //this.formComponent.printQuoteSrv.afterDataLoadSubject.next(true);
     });
  }


  quoteHeader(res: IQuotes) :string{
    let qHeader = '- '+res.sequence_number;
    if (res.modification_number!='0'){
      qHeader+=' ('+res.modification_number+') -';
    }
    qHeader +=' '+ this.statusHeader;
    if (res.cancel_reason_id){
      qHeader += ' (' + this.formComponent.cancel_reason + ')'
    }
    return qHeader;
  }

  sorting(array: IBookingItem[]): IBookingItem[] {
    return array.sort((a, b) => a.option.order - b.option.order || +a.option.id - +b.option.id);
  }

  orderSummaryCharges(summary: ISummaryCharges): ISummaryCharges {
    let summaryItemsSorted: IBookingItem[] = this.sorting(summary.items);
    summary.items = summaryItemsSorted;
    return summary;
  }

  ngAfterViewChecked(){
    let sourceCpl = this.formComponent.sourceComplete;
    let driverCpl = this.formComponent.driverComplete;
    let stationOutCpl = this.formComponent.stationOutComplete;
    let stationInCpl = this.formComponent.stationInComplete;
    let agentCpl = this.formComponent.agentComplete;
    let optionsCpl = this.formComponent.optionsItemsComplete;
    let companyCpl = this.formComponent.companyComplete;

    // wait until all after data load methods complete and run one time this if to activate fom component calcs
    if(this.quoteEditOneTime && sourceCpl && driverCpl
      && stationInCpl && stationOutCpl && agentCpl && optionsCpl && companyCpl){
      // title of breadcrumb
      this.formComponent.payersCollection();
      this.formComponent.saveItemsInit('rental_charges', 'summary_charges.rental_fee');
      this.formComponent.breadcrumbsSrv.TitleSubject.next(this.quoteHeader(this.resQ));
      this.formComponent.printQuoteSrv.afterDataLoadSubject.next(true);
      this.quoteEditOneTime = false;
      console.log('quote edit afterDL activated');

          //first time print
        if (this.formComponent.printQuoteSrv.createFirstTime.getValue()) {//cause in create is too early
          // console.log('submitted quote edit');
            this.formComponent.matDialog.open(PrintQuoteComponent, {
              width: '80%',
              height: '80%',
              data: this.formComponent.form.controls.id.value,
              autoFocus: false
            });
          this.formComponent.printQuoteSrv.createFirstTime.next(false);
        }
    }

  }


}




