import { Component, ElementRef, HostListener, Injector, Input, OnInit, ViewChild } from '@angular/core';
import { MatSelect } from '@angular/material/select';
import moment from 'moment';
import { retry, delay, Subscription } from 'rxjs';
import { CreateFormComponent } from 'src/app/create-form/create-form.component';
import { IPayers } from 'src/app/rental/rental-form/rental-form.component';
import { IUser } from 'src/app/user/user.interface';
import { ApiService } from 'src/app/_services/api-service.service';
import { AuthService } from 'src/app/_services/auth.service';
import { PaymentFormComponent } from '../payment-form/payment-form.component';
import { IPayment } from '../payment.interface';
import { PaymentService } from '../payment.service';

@Component({
  selector: 'app-create-payment',
  templateUrl: './create-payment.component.html',
  styleUrls: ['./create-payment.component.scss'],
  providers: [{provide: ApiService, useClass: PaymentService}]
})
export class CreatePaymentComponent extends CreateFormComponent<IPayment> implements OnInit{
  @ViewChild(PaymentFormComponent, {static: true}) formComponent!: PaymentFormComponent;


apiSrv!: PaymentService<IPayment>;

currentDate = moment().format('YYYY-MM-DD HH:mm');

comPrefData:any = [];
  test ={id:'',name:''};//for places needs as autocomplete selector 2 values(name,id)
  user:IUser;
  header:string;
  @Input() typePayment: string|null;
  @Input() rental: boolean | null = null;
  @Input() payers!: IPayers;
  @Input() rental_id!: string;
  @Input() comesFromNew!: string|boolean;
  @Input() excess!: number|null;
  @Input() discount_fee!: number | null;

ngOnInit() {
  this.formComponent.type = this.router.url.split('/')[2];
  // this.type = this.route.snapshot.paramMap.get('type');
  this.apiSrv.setType(this.formComponent.type);
  super.ngOnInit();
   if(this.typePayment!=null){//invoked by dialog
     this.formComponent.type=this.typePayment;
     console.log(this.typePayment);
     this.formComponent.rental = this.rental;
     this.formComponent.payers = this.payers;

     this.formComponent.rental_id=this.rental_id;
     this.formComponent.form.controls.rental_id.patchValue(this.rental_id);
     if(this.comesFromNew=='create'){
       this.comesFromNew = true;
     }else{
       this.comesFromNew = false;
     }
     console.log(this.comesFromNew);
   }
    this.apiSrv.setType(this.formComponent.type);
   // console.log(this.formComponent.type);
 // this.handleHeader(this.formComponent.type);
  this.formComponent.form.controls['payment_datetime'].patchValue(this.currentDate);
  setTimeout(() => this.formComponent.datetime.timepickerControl.patchValue(moment(this.currentDate).format('HH:mm')), 500);
   this.formComponent.form.controls['payment_method'].patchValue('cash');
  this.formComponent.comPrefSrv.edit().subscribe((res: any) => { this.comPrefData = res, this.formComponent.form.controls['brand_id'].setValue(this.comPrefData?.booking_source?.brand_id);
  this.formComponent.form.controls['station_id'].patchValue(this.comPrefData?.station_id);
  this.test.id = this.comPrefData?.place?.id;
  this.test.name = this.comPrefData?.place?.name;
  this.formComponent.form.controls['place'].patchValue(this.test); });

  this.user = JSON.parse(localStorage.getItem('loggedUser'));
  this.formComponent.form.controls['user_id'].patchValue(this.user.id);

  this.formComponent.isDialog = this.excess;//remaining field
  this.formComponent.loadSrv.loadingOff();
}


ngOnDestroy() {
    this.apiSrv.setType(null);
  }


  // @HostListener('body:blur', ['$event'])
  // amountEvent(event: Event) {
  //   if (this.rental) {
  //     if (this.formComponent.form.controls['payer'].value){
  //       let payerType = this.formComponent.form.controls['payer'].value;
  //       this.formComponent.form.controls.amount.patchValue(this.payers[payerType].debt);
  //     }
  //   }
  //   console.log(this.payers);
  //  // console.log(this.payers[payerType]);
  // }

  amountPaymentCreate(event:string){
   // console.log(event);
    if (this.rental) {
     // if (this.formComponent.form.controls['payer'].value) {
       // let payerType = this.formComponent.form.controls['payer'].value;
      console.log(this.payers);
      this.formComponent.form.controls.amount.patchValue(this.payers[event].debt - this.discount_fee - this.payers[event].paid);
      this.formComponent.form.controls.remaining.patchValue(this.payers[event].debt - this.discount_fee - this.payers[event].paid);
      //}
    }
  }


 amountRefundCreate(event:string){
   // console.log(event);
   if (this.rental) {
     // if (this.formComponent.form.controls['payer'].value) {
     // let payerType = this.formComponent.form.controls['payer'].value;
     console.log(this.payers);
     this.formComponent.form.controls.amount.patchValue(this.payers[event].paid - this.payers[event].debt - this.discount_fee);
     this.formComponent.form.controls.remaining.patchValue(this.payers[event].paid - this.payers[event].debt - this.discount_fee);
     //}
   }
 }

  amountPreAuthCreate(event: string) {
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

  amountRefundPreAuthCreate(event: string) {
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
