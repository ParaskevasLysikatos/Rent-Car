import { ConsoleLogger } from '@angular/compiler-cli/private/localize';
import { Component, HostListener, Injector, Input, ViewChild } from '@angular/core';
import { Console } from 'console';
import moment from 'moment';
import { EditFormComponent } from 'src/app/edit-form/edit-form.component';
import { IPayers } from 'src/app/rental/rental-form/rental-form.component';
import { SelectorService } from 'src/app/_selectors/selector/selector.service';
import { ApiService } from 'src/app/_services/api-service.service';
import { PaymentFormComponent } from '../payment-form/payment-form.component';
import { IPayment } from '../payment.interface';
import { PaymentService } from '../payment.service';

@Component({
  selector: 'app-edit-payment',
  templateUrl: './edit-payment.component.html',
  styleUrls: ['./edit-payment.component.scss'],
  providers: [{provide: ApiService, useClass: PaymentService}]
})
export class EditPaymentComponent extends EditFormComponent<IPayment> {
  @ViewChild(PaymentFormComponent, {static: true}) formComponent!: PaymentFormComponent;

  @Input() type!: string|null;
  @Input() rental: boolean | null = null;
  @Input() payers!: IPayers;
  @Input() payerType!: any;
  @Input() excess!: number|null;
  @Input() discount_fee!: number | null;
  @Input() comesFromNew!: string | boolean; //for booking payment to disable rental selector


  apiSrv!: PaymentService<IPayment>;
  flag:boolean = true;
  customUrlLoader: string;

  ngOnInit() {
    console.log(this.type);
    if (this.type == null) {
      this.type = this.router.url.split('/')[2];
     // this.type = this.route.snapshot.paramMap.get('type');
      this.apiSrv.setType(this.type);
      super.ngOnInit();
      this.formComponent.type = this.type;
      this.flag = false;
    } else {//means through dialog
      super.ngOnInit();
      this.apiSrv.setType(this.type);
      this.formComponent.type = this.type;
      this.formComponent.rental = this.rental;
      this.formComponent.payers = this.payers;

      if (this.comesFromNew == 'create') {//for booking payment to disable rental selector
        this.comesFromNew = true;
      } else {
        this.comesFromNew = false;
      }

      this.formComponent.rental_id = this.object.rental_id;
      this.formComponent.conInvoice = this.object.conInvoice;
      this.formComponent.conRental = this.object.conRental;
      this.formComponent.isDialog = this.excess;//remaining field
      setTimeout(() => this.formComponent.datetime.timepickerControl.patchValue(moment(this.object.payment_datetime).format('HH:mm')), 500);
      console.log(this.object);
    }

   // this.customUrlLoader = this.formComponent.urlSrv.url;
   // console.log(this.customUrlLoader);
    // if (this.customUrlLoader.includes('rentals') || this.customUrlLoader.includes('bookings')||this.customUrlLoader.includes('quotes')){
    //   setTimeout(() => {
    //     this.formComponent.loadSrv.loadingOff();
    //   },2000);

    // }
  }

  ngOnDestroy() {
    this.apiSrv.setType(null);
  }

  afterDataLoad(res:IPayment) {//will not run if through dialog modal
    //this.formComponent.payerData = res.payer.name;
    this.formComponent.rental_id = res.rental_id;
    this.formComponent.conInvoice = res.conInvoice;
    this.formComponent.conRental = res.conRental;
    let date = res.payment_datetime;
    let time: string = this.formComponent.datetime.timepickerControl.value;
    this.formComponent.datetime.timepickerControl.patchValue(moment(date).hour(+String(time).substring(0, 2)).minute(+String(time).substring(3, 6)).format('HH:mm'));
   //console.log(res);
    let year_month = res.credit_card_month_year;
    let month = year_month.split('-');
    if(+month[1]<10){
      month[1] = '0' + month[1];
    }
    this.formComponent.form.controls.credit_card_month_year.patchValue(month[0] + '-' + month[1]);
    this.formComponent.loadSrv.loadingOff();
  }

  @HostListener('body:click', ['$event'])
  placeAndDocEvent(event: Event) {
    if(this.flag){
      this.formComponent.form.controls.place.patchValue(this.object.place);
      this.formComponent.form.controls.documents.patchValue(this.object.documents);
      this.formComponent.form.controls.payer.patchValue(this.payerType);//{type:driver,name:'',paid,debt}

    }
   // console.log(this.formComponent.form.controls.place.value);
    // console.log(this.formComponent.form.controls.payer.value);

    this.flag = false;
   // console.log(this.payers);
  //  console.log(this.payers[payerType].debt);

  }

  amountPaymentEdit(event: string) {
    // console.log(event);
    if (this.rental) {
      // if (this.formComponent.form.controls['payer'].value) {
      // let payerType = this.formComponent.form.controls['payer'].value;
      this.formComponent.form.controls.amount.patchValue(this.payers[event].debt - this.discount_fee - this.payers[event].paid);
      this.formComponent.form.controls.remaining.patchValue(this.payers[event].debt - this.discount_fee - this.payers[event].paid);
      //}
    }
  }

  amountRefundEdit(event: string) {
    // console.log(event);
    if (this.rental) {
      // if (this.formComponent.form.controls['payer'].value) {
      // let payerType = this.formComponent.form.controls['payer'].value;
      // console.log(this.payers);
      this.formComponent.form.controls.amount.patchValue(this.payers[event].paid - this.payers[event].debt - this.discount_fee);
      this.formComponent.form.controls.remaining.patchValue(this.payers[event].paid - this.payers[event].debt - this.discount_fee);
      //}
    }
  }

  amountPreAuthEdit(event: string) {
    // console.log(event);
    if (this.rental) {
      // if (this.formComponent.form.controls['payer'].value) {
      // let payerType = this.formComponent.form.controls['payer'].value;
      console.log(this.payers);
      this.formComponent.form.controls.amount.patchValue(this.excess);
      this.formComponent.form.controls.remaining.patchValue(this.excess);
      //}
    }
  }

  amountRefundPreAuthEdit(event: string) {
    // console.log(event);
    if (this.rental) {
      // if (this.formComponent.form.controls['payer'].value) {
      // let payerType = this.formComponent.form.controls['payer'].value;
      console.log(this.payers);
      this.formComponent.form.controls.amount.patchValue(this.excess);
      this.formComponent.form.controls.remaining.patchValue(this.excess);
      //}
    }
  }

}
