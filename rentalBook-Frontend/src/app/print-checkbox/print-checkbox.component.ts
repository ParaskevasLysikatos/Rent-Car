import { Component, HostListener, Inject, OnInit } from '@angular/core';
import { FormGroup, FormControl, FormBuilder, FormArray } from '@angular/forms';
import { MAT_DIALOG_DATA, MatDialog } from '@angular/material/dialog';
import { interval, Subscription, timer } from 'rxjs';
import { delay, first, last, take } from 'rxjs/operators';
import { PrintBookingComponent } from '../booking/print-booking/print-booking.component';
import { InvoicePrintComponent } from '../invoices/invoice-print/invoice-print.component';
import { IPayment } from '../payment/payment.interface';
import { PrintPaymentComponent } from '../payment/print-payment/print-payment.component';
import { PrintQuoteComponent } from '../quotes/print-quote/print-quote.component';
import { PrintRentalComponent } from '../rental/print-rental/print-rental.component';
import { Print, PrintCheckboxService } from './print-checkbox.service';



@Component({
  selector: 'app-print-checkbox',
  templateUrl: './print-checkbox.component.html',
  styleUrls: ['./print-checkbox.component.scss']
})
export class PrintCheckboxComponent implements OnInit {

  myForm: FormGroup;
  selected:boolean=false;

  constructor(@Inject(MAT_DIALOG_DATA) public data: any, protected fb: FormBuilder,
              protected printCheckboxSrv:PrintCheckboxService) {

  }

  ngOnInit() {
    this.myForm = this.fb.group({
      checkboxes: this.fb.array([]),
    });

    console.log(this.data);
    for (let item in this.data) {
      if (item == 'IamPayment') {
        console.log('iam payment');
        this.addCheckbox(this.data?.id, this.data?.sequence_number,PrintPaymentComponent);
      }
      else if (item == 'IamInvoice') {
        console.log('iam invoice');
        this.addCheckbox(this.data?.id, this.data?.sequence_number, InvoicePrintComponent);
      }
      else if (item == 'IamRental') {
        console.log('iam rental');
        this.addCheckbox(this.data?.id, this.data?.sequence_number, PrintRentalComponent);
       for(let payment of this.data?.payments){
         this.addCheckbox(payment.id, payment.sequence_number, PrintPaymentComponent);
       }
        for (let invoice of this.data?.invoices) {
          this.addCheckbox(invoice.id, invoice.sequence_number, InvoicePrintComponent);
        }
      }
      else if (item == 'IamBooking') {
        console.log('iam booking');
        this.addCheckbox(this.data?.id, this.data?.sequence_number, PrintBookingComponent);
        for (let payment of this.data?.payments) {
          this.addCheckbox(payment.id, payment.sequence_number, PrintPaymentComponent);
        }
      }
      else if (item == 'IamQuote') {
        console.log('iam quote');
        this.addCheckbox(this.data?.id, this.data?.sequence_number, PrintQuoteComponent);
      }

    }
  }


  checkboxes(): FormArray {
    return this.myForm.get("checkboxes") as FormArray;
  }


  newCheckbox(id:number,sequence_number:string,component: any): FormGroup {
    return this.fb.group({
      id: id,
      sequence_number: sequence_number,
      checked:false,
      component:component
    })
  }


  addCheckbox(id: number, sequence_number: string,component: any) {
    this.checkboxes().push(this.newCheckbox(id,sequence_number,component));
  }

  checkCheckbox(index: number) {
    this.checkboxes().at(index).value.checked = !this.checkboxes().at(index).value.checked;
    this.checkPrintEnable();
  }




  ShowAllSelectedPrints(){
    for (let a = 0; a <= this.checkboxes().length; a++){
      if (this.checkboxes().at(a)?.value.checked == true) {
      // this.ShowPrint(this.checkboxes().at(a)?.value.component, this.checkboxes().at(a)?.value.id);
        this.printCheckboxSrv.arrayPrints.push(new Print(this.checkboxes().at(a)?.value.component,this.checkboxes().at(a)?.value.id));
      }
    }
    this.checkboxes().clear();//cause second time holds checkboxes and ruins print;
   // console.log(this.printCheckboxSrv.arrayPrints);
    this.printCheckboxSrv.showCurrentPrint.next(this.printCheckboxSrv.arrayPrints.pop());
   // console.log(this.printCheckboxSrv.arrayPrints);
    this.printCheckboxSrv.showCurrentPrint.pipe(delay(500),first()).subscribe((res:Print) =>this.printCheckboxSrv.ShowPrint(res.component,res.data));
  }

  checkPrintEnable(){
    for (let a = 0; a <= this.checkboxes().length; a++) {
      if (this.checkboxes().at(a)?.value.checked == true) {
        this.selected=true;
        break;
      }else{
        this.selected = false;
      }
    }
  }


  // @HostListener('window:beforeunload') //<-- Do NOT put a semicolon here
  // async destroy() {
  //   await this.ngOnDestroy()
  // }

  // ngOnDestroy(){
  //   this.printCheckboxSrv.arrayPrints = [];
  //   this.printCheckboxSrv.showCurrentPrint.next(null);
  // }


}
