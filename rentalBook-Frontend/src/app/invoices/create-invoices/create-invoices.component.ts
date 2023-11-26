import { Component, Injector, ViewChild } from '@angular/core';
import { FormBuilder, FormGroup,FormControl, Validators } from '@angular/forms';
import { CreateFormComponent } from 'src/app/create-form/create-form.component';
import { AbstractSelectorComponent } from 'src/app/_selectors/abstract-selector/abstract-selector.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { InvoicesFormComponent } from '../invoices-form/invoices-form.component';
import { IInvoices } from '../invoices.interface';
import { InvoicesService } from '../invoices.service';

@Component({
  selector: 'app-create-invoices',
  templateUrl: './create-invoices.component.html',
  styleUrls: ['./create-invoices.component.scss'],
  providers: [{provide: ApiService, useClass: InvoicesService}]
})
export class CreateInvoicesComponent extends CreateFormComponent<IInvoices> {
  @ViewChild(InvoicesFormComponent, {static: true}) formComponent!: InvoicesFormComponent;
  ItemsCreate!:any[];
  currentDate = new Date();
  fpa:number=0
  @ViewChild('brandC', { static: false }) brandC_id_Ref: AbstractSelectorComponent<any>;
  @ViewChild('stationC', { static: false }) stationC_id_Ref: AbstractSelectorComponent<any>;
  @ViewChild('rentalC', { static: false }) rentalC_id_Ref: AbstractSelectorComponent<any>;
  @ViewChild('customerC', { static: false }) customerC_id_Ref: AbstractSelectorComponent<any>;

  ngOnInit(): void {
    super.ngOnInit();

    this.formComponent.combinedSrv.getInvoices().subscribe((res) => {
      this.brandC_id_Ref.selector.data = res.brands;
      this.stationC_id_Ref.selector.data = res.stations;
      this.rentalC_id_Ref.selector.data = res.rentals;
      this.customerC_id_Ref.selector.data = res.customers;
    });

    this.formComponent.form.controls.invoicee.addValidators(Validators.required);
    this.formComponent.comPrefSrv.edit().subscribe(res => {
      this.fpa = res.vat;
      this.formComponent.form.controls['fpa'].setValue(this.fpa);
      this.formComponent.form.controls['station_id'].setValue(res.station_id);
    });//will come from η εταιρια μου

   // this.formComponent.form.controls['date'].enable();
    this.formComponent.form.controls['date'].setValue(this.currentDate);
    this.formComponent.read=false;

    setTimeout(() => { this.formComponent.loadSrv.loadingOff(); }, 2000);
 }


addRow() {
  this.formComponent.rows().push(this.formComponent.newRow());
}

removeRow(rowIndex:number) {
  this.formComponent.rows().removeAt(rowIndex);
  this.calcFinal();
}

calcP(rowIndex:number){
let price= this.formComponent.rows().at(rowIndex).value.price;
let quantity= this.formComponent.rows().at(rowIndex).value.quantity;

this.formComponent.rows().at(rowIndex).get('total')?.setValue(quantity*price);

  this.calcFinal();
}


calcQ(rowIndex:number){
 let price=this.formComponent.rows().at(rowIndex).value.price;
 let quantity=this.formComponent.rows().at(rowIndex).value.quantity;

 this.formComponent.rows().at(rowIndex).get('total')?.patchValue(quantity*price);
  this.calcFinal();
}


calcT(rowIndex:number){
let quantity= this.formComponent.rows().at(rowIndex).value.quantity;
let total=this.formComponent.rows().at(rowIndex).value.total;

 this.formComponent.rows().at(rowIndex).get('price')?.patchValue((total/quantity).toFixed(2));
  this.calcFinal();
}

calcFinal(){
  let arrayTotals:number[] = [];
  for(let row of this.formComponent.rows().controls){
   arrayTotals.push(row.get('total')?.value)
  }
  let FinalTotal = 0;
  arrayTotals.forEach(number => {
  FinalTotal += number;
})
  this.formComponent.form.controls.sub_discount_total.patchValue(Math.round(FinalTotal-(FinalTotal*(this.fpa/100))));
  this.formComponent.form.controls.final_fpa.patchValue(Math.round(FinalTotal*(this.fpa/100)));
  this.formComponent.form.controls.final_total.patchValue(FinalTotal);

}


}
