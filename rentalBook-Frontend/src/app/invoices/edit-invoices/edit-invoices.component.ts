import { Component, Injector, ViewChild } from '@angular/core';
import { EditFormComponent } from 'src/app/edit-form/edit-form.component';
import { IPayment } from 'src/app/payment/payment.interface';
import { IRental } from 'src/app/rental/rental.interface';
import { ApiService } from 'src/app/_services/api-service.service';
import { InvoicePrintComponent } from '../invoice-print/invoice-print.component';
import { InvoicesFormComponent } from '../invoices-form/invoices-form.component';
import { IInvoices } from '../invoices.interface';
import { InvoicesService } from '../invoices.service';

@Component({
  selector: 'app-edit-invoices',
  templateUrl: './edit-invoices.component.html',
  styleUrls: ['./edit-invoices.component.scss'],
  providers: [{provide: ApiService, useClass: InvoicesService}]
})
export class EditInvoicesComponent extends EditFormComponent<IInvoices> {
  @ViewChild(InvoicesFormComponent, {static: true}) formComponent!: InvoicesFormComponent;
  customUrlLoader: string;

  ngOnInit(){
    super.ngOnInit();
   // this.customUrlLoader = this.formComponent.urlSrv.url;
  //  console.log(this.customUrlLoader);
    // if (this.customUrlLoader.includes('rentals') || this.customUrlLoader.includes('bookings') || this.customUrlLoader.includes('quotes')) {
    //   setTimeout(() => {
    //     this.formComponent.loadSrv.loadingOff();
    //   }, 2000);

    // }
  }

 afterDataLoad(res:IInvoices) {
 // this.formComponent.instanceData = res.instance;
  this.formComponent.brandData = res.brand;
  this.formComponent.Items = res.items;
   this.formComponent.rental_id = res.rental_id;

   if (this.formComponent.printInvSrv.createFirstTime.getValue() || this.formComponent.form.controls.checkPrint.value) {//cause in create is too early
     // console.log('sumitted quote edit');
     setTimeout(() => {
       this.formComponent.matDialog.open(InvoicePrintComponent, {
         width: '90%',
         height: '90%',
         data: res?.id,
        autoFocus: false
     });
   }, 500);

     this.formComponent.printInvSrv.createFirstTime.next(false);
   }
   this.formComponent.loadSrv.loadingOff();

  }

  // submitted = (res) => {
  //   console.log(res);
  //  if(this.formComponent.form.controls.checkPrint.value){
  //      this.formComponent.matDialog.open(InvoicePrintComponent, {
  //        width: '90%',
  //        height: '90%',
  //        data: res?.id,
  //        autoFocus: false
  //      });
  //    }
  //  }



}
