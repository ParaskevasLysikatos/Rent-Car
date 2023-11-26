
import { DecimalPipe } from '@angular/common';
import { Component, ElementRef, EventEmitter, HostListener, Injector, Input, OnChanges, OnInit, Output, SimpleChanges, TemplateRef, ViewChild } from '@angular/core';
import { AbstractControl, FormArray, FormControl, FormGroup, FormGroupDirective } from '@angular/forms';
import { MatDialog, MatDialogRef } from '@angular/material/dialog';
import { TooltipPosition } from '@angular/material/tooltip';
import { Router } from '@angular/router';
import { delay } from 'rxjs';
import { AbstractFormComponent } from 'src/app/abstract-form/abstract-form.component';
import { IBookingItem } from 'src/app/booking-item/booking-item.interface';
import { PrintBookingService } from '../booking/print-booking/print-booking.service';
import { PrintQuoteService } from '../quotes/print-quote/print-quote.service';
import { PrintRentalService } from '../rental/print-rental/print-rental.service';
import { IPayers} from '../rental/rental-form/rental-form.component';
import { ITypes } from '../types/types.interface';
import { TypesService } from '../types/types.service';

@Component({
  selector: 'app-summary-charges',
  templateUrl: './summary-charges.component.html',
  styleUrls: ['./summary-charges.component.scss']
})


export class SummaryChargesComponent extends AbstractFormComponent implements OnInit {

@Input() formGroupName: string; //the whole form
@Input() durationInput!: number; // the duration for items
@Input() summary_payers: IPayers; // calculated payers
@Input() paid: number=0; // when payments are made (booking-rental)
@Input() extra_charges: number | null=0;//(rental,only when more than one invoice are made)
form!: FormGroup;

  positionOptions: TooltipPosition[] = ['right', 'left', 'above', 'below'];
  position = new FormControl(this.positionOptions[0]);
  position2 = new FormControl(this.positionOptions[1]);
  position3 = new FormControl(this.positionOptions[2]);
  position4 = new FormControl(this.positionOptions[3]);

  // summary_charges: this.fb.group({
  //   payer: [],//constructed
  //   subcharges_fee: [],//ok
  //   rate: [],//ok
  //   manual_agreement: [],//ok
  //   charge_type_id: [],//ok
  //   distance: [],//ok
  //   distance_rate: [],//ok
  //   rental_fee: [],//ok
  //   transport_fee: [],//ok
  //   insurance_fee: [],//ok
  //   options_fee: [],//ok
  //   fuel_fee: [],//ok
  //   discount: [],//ok
  //   discount_fee: [],//calculated
  //   vat: [],//ok
  //   vat_fee: [],//ok
  //   vat_included:[], // from booking
  //   voucher: [],//ok
  //   total_paid: [],//ok
 //    total_net: [],//ok
  //   total: [],//ok
  //   balance: [],//ok
  //   items: this.fb.array([]),//from method
  //   }),

  @ViewChild('rental_charges') rentalCharges!: any;
  @ViewChild('transport') transport!: any;
  @ViewChild('insurances') insurances!: any;
  @ViewChild('extras') extras!: any;


  @ViewChild('subcharges', { static: false }) subcharges_Ref:ElementRef;
  @ViewChild('discount_fee', { static: false }) discount_fee_Ref: ElementRef;

  @ViewChild('total', { static: false }) total_Ref: ElementRef;
  @ViewChild('vat_fee', { static: false }) vat_fee_Ref: ElementRef;
  //@ViewChild('total_paid', { static: true }) total_paid_Ref: ElementRef;
  @ViewChild('final', { static: false }) final_Ref: ElementRef;
  @ViewChild('rate', { static: false }) rate_Ref: ElementRef;

  @ViewChild('extraKm', { static: false }) extraKm_Ref: ElementRef;
  @ViewChild('freeKm', { static: false }) freeKm_Ref: ElementRef;
  freeKm: number=0;
  extraKm: number=0;
  fuelCharge: number = 0;

  itemsSnapshot!: FormArray;
  dialogRef!: MatDialogRef<any>;
  readonly Number = Number;
  @Input() groups: ITypes[];
  checkUrl!:string;

  @Output() finalIncreased: EventEmitter<number> = new EventEmitter();
  @Output() rateIncreased: EventEmitter<number> = new EventEmitter();

  @Output() extraKmIncreased: EventEmitter<number> = new EventEmitter();
  @Output() freeKmIncreased: EventEmitter<number> = new EventEmitter();

  @Output() actParent: EventEmitter<boolean> = new EventEmitter();

  constructor(protected injector: Injector, protected dialogSrv: MatDialog,public router: Router,
    protected decimalPipe: DecimalPipe, private rootFormGroup: FormGroupDirective,
    public printRentalSrv: PrintRentalService, public printBookingSrv: PrintBookingService,
    public printQuoteSrv: PrintQuoteService, public typesSrv: TypesService<ITypes> ) {
    super(injector);
  }

  ngOnInit() {
    this.checkUrl = this.router.url.split('/')[1];
    this.form = this.rootFormGroup.control.get(this.formGroupName) as FormGroup;

    // this.typesSrv.get({}, undefined, -1).subscribe((res) => { // fill the group selector with options
    //   this.groups = res.data;
    // });
  }

  // ngOnChanges(changes: SimpleChanges) {
  //   console.log('OnChanges');
  //   console.log(changes);
  // }


  vatInclude(){
    if (this.form.get('vat_included')?.value){
      this.form.get('vat_included')?.patchValue(false);
    }else{
      this.form.get('vat_included')?.patchValue(true);
    }
    console.log(this.form.get('vat_included').value);
  }

  subcharges_feeCalc(){
    this.form.get('subcharges_fee')?.patchValue(this.roundTo(parseFloat(this.subcharges_Ref.nativeElement.value),2));
    console.log(this.subcharges_Ref.nativeElement.value);
    console.log(this.form?.get('subcharges_fee')?.value);
  }

  discount_feeCalc(){
    this.form.get('discount_fee')?.patchValue(this.roundTo(parseFloat(this.discount_fee_Ref.nativeElement.value),2));
    console.log(this.form?.get('discount_fee')?.value);
  }

  vat_feeCalc() {
    this.form.get('vat_fee')?.patchValue(this.roundTo(parseFloat(this.vat_fee_Ref.nativeElement.value),2));
    console.log(this.form?.get('vat_fee')?.value);
  }

  totalCalc(){
    this.calcManualDiscount();
    this.form.get('total')?.patchValue(this.roundTo(parseFloat(this.total_Ref.nativeElement.value),2));

    if (this.summary_payers != null) {
       this.form.get('total_paid')?.patchValue(this.paid + this.summary_payers['agent'].paid);
     // this.form.get('total_net')?.patchValue((this.paid + this.summary_payers['agent'].paid - this.roundTo(parseFloat(this.vat_fee_Ref.nativeElement.value),2)));
      this.form.get('total_net')?.patchValue(this.roundTo(parseFloat(this.total_Ref.nativeElement.value) - parseFloat(this.vat_fee_Ref.nativeElement.value), 2));
    }else{
      this.form.get('total_paid')?.patchValue(this.paid);
    //  this.form.get('total_net')?.patchValue((this.paid  - this.roundTo(parseFloat(this.vat_fee_Ref.nativeElement.value),2)));
      this.form.get('total_net')?.patchValue(this.roundTo(parseFloat(this.total_Ref.nativeElement.value) - parseFloat(this.vat_fee_Ref.nativeElement.value), 2));
    }
   // setTimeout(() => { this.form.get('balance')?.patchValue(this.roundTo(parseFloat(this.final_Ref.nativeElement.value), 2) - this.paid); }, 500);
    this.voucherCalc();
}


 voucherCalc(){
   if (this.summary_payers!= null) {
     this.form.get('voucher')?.patchValue(this.summary_payers['agent'].debt.toFixed(2));//voucher is what agent has been charged
     this.form.get('balance')?.patchValue(this.roundTo(parseFloat(this.final_Ref.nativeElement.value), 2) - this.paid - +this.summary_payers['agent'].debt.toFixed(2));//what driver and company has paid - has been charged
   }
   else{
     this.form.get('voucher')?.patchValue(0);//voucher is what agent has been charged
     this.form.get('balance')?.patchValue(this.roundTo(parseFloat(this.final_Ref.nativeElement.value),2) - this.paid);//what driver and company has paid - has been charged
   }
 }

  calcManualDiscount(){
   // let discount_fee = this.form.get('discount_fee').value;
   // let discount = this.form.get('discount').value;
    let total = this.roundTo(parseFloat(this.total_Ref.nativeElement.value),2);
    let final = this.roundTo(parseFloat(this.final_Ref.nativeElement.value),2);
    console.log(final+ ' final');
    console.log(total+' total');
    if(total!=final){//check change of final
      if(final<total){ // the final was reduced so discount must recalc
        this.form.get('discount')?.patchValue(this.roundTo(((total-final)/total)*100,2));
      }else{//the final was increased so discount goes zero and charge goes to rental charge
        this.form.get('discount')?.patchValue(0);
        this.finalIncreased.emit(final-total);
      }

    }
  }

  rateCalc() {
    let rate = +this.rate_Ref.nativeElement.value;
   // this.form.controls.rental_fee.patchValue(this.roundTo(+rate * +this.items.at(0).get('duration').value, 2));
    this.rateIncreased.emit(rate);
  }

  extraKmCalc(){
    let extraKm= +this.extraKm_Ref.nativeElement.value;
    this.extraKmIncreased.emit(extraKm);
  }

  freeKmCalc() {
    let freeKm =  +this.freeKm_Ref.nativeElement.value;
    this.freeKmIncreased.emit(freeKm);
  }

 activateParentCalc(){
   this.actParent.emit(true);
 }


  @HostListener('document:change', ['$event'])
  EventChange(event: Event) {
    if (this.subcharges_Ref.nativeElement.value != this.form.get('subcharges_fee')?.value) {
      console.log('some change sub');
      this.subcharges_feeCalc();
      this.activateParentCalc();
    }
    if (this.discount_fee_Ref.nativeElement.value != this.form.get('discount_fee')?.value) {
      console.log('some change disc');
      this.discount_feeCalc();
      this.activateParentCalc();
    }
    if (this.rate_Ref.nativeElement.value != this.form.get('rate')?.value) {
      console.log('some change rate');
      this.rateCalc();
      // this.activateParentCalc();
    }
    if (this.extraKm_Ref.nativeElement.value != this.extraKm) {
      console.log('some change distance rate');
      this.extraKm = +this.extraKm_Ref.nativeElement.value;
      this.extraKmCalc();
      //this.activateParentCalc();
    }
    if (this.freeKm_Ref.nativeElement.value != this.freeKm) {
      console.log('some change distance');
      this.freeKm = +this.freeKm_Ref.nativeElement.value;
      this.freeKmCalc();
    }
    if (this.form.get('fuel_fee')?.value != this.fuelCharge) {
      console.log('some change fuel');
      this.fuelCharge = this.form.get('fuel_fee').value;
      this.activateParentCalc();
    }
    this.totalCalc();
  }

  @HostListener('document:click', ['$event'])
  EventClick(event: Event) {
    if (this.subcharges_Ref.nativeElement.value != this.form.get('subcharges_fee')?.value) {
      console.log('some click sub');
      this.subcharges_feeCalc();
      this.activateParentCalc();
    }
    if (this.discount_fee_Ref.nativeElement.value != this.form.get('discount_fee')?.value) {
      console.log('some click disc');
      this.discount_feeCalc();
      this.activateParentCalc();
    }
    if (this.rate_Ref.nativeElement.value != this.form.get('rate')?.value) {
      console.log('some click rate');
      this.rateCalc();
      // this.activateParentCalc();
    }
    if (this.extraKm_Ref.nativeElement.value != this.extraKm) {
      console.log('some change click rate');
      this.extraKm = +this.extraKm_Ref.nativeElement.value;
      this.extraKmCalc();
      //this.activateParentCalc();
    }
    if (this.freeKm_Ref.nativeElement.value != this.freeKm) {
      console.log('some click distance');
      this.freeKm = +this.freeKm_Ref.nativeElement.value;
      this.freeKmCalc();
    }
    if (this.form.get('fuel_fee')?.value != this.fuelCharge) {
      console.log('some click fuel');
      this.fuelCharge = this.form.get('fuel_fee').value;
      this.activateParentCalc();
    }
    this.totalCalc();
  }


  get items() {
    return this.form.get('items') as FormArray;
  }


  showItems(evt: Event,template: TemplateRef<any>,fee:string) {
   // console.log(this.form);
    evt.stopImmediatePropagation();
    evt.preventDefault();
    this.itemsSnapshot = this.form?.get('items')?.value;
    this.dialogRef = this.dialogSrv.open(template, {
      maxHeight: '95vh',
      disableClose: true
    });
  }

  // calcDiscount(evt: Event): void {
  //   const target = evt.target as HTMLInputElement;
  //   if (target.value <= this.form.get('subcharges_fee')?.value) {
  //     const discount_fee = this.form.get('subcharges_fee')?.value - Number(target.value);
  //     const discount = discount_fee / this.form.get('subcharges_fee')?.value * 100;
  //     this.form.get('discount')?.setValue(discount);
  //     this.form.get('discount_fee')?.setValue(discount_fee);
  //   }
  // }

  changeQuantity(itemForm: AbstractControl): void {
    const item = itemForm.value;
    console.log(item);

    if (item.option.option_type != 'rental_charges') {
      //this.form.get('rate').patchValue(item.cost)
      item.duration = this.durationInput;
    }
    if (item?.quantity) {// casual case
      item.total_cost = item.quantity * item.cost;
      if (item.daily) {
        item.total_cost *= item.duration;

        if (item.option.cost_max != 0 && item.total_cost > item.option.cost_max && item.option.cost_max != null) {
          item.total_cost = item.option.cost_max;
        }
      }

      itemForm.patchValue({
        duration: item.duration,
        total_cost: this.roundTo(item.total_cost, 2),
      }, { emitEvent: false });
    }

    if(!item?.quantity) {//de-select quantity
      itemForm.patchValue({
        duration: item.duration,
        total_cost: this.roundTo(0, 2),
      }, { emitEvent: false });
    }
   // console.log(item);
  }


   changeTotalCost(itemForm: AbstractControl): void {
    const item = itemForm.value;
     if (item.option.option_type != 'rental_charges') {
       //this.form.get('rate').patchValue(item.cost)
       item.duration = this.durationInput;
     }

    if (item?.quantity) {// casual case
      let cost = item.total_cost / item.quantity;
       if (item.daily) {
         cost /= item.duration;

         if (item.option.cost_max != 0 && item.total_cost > item.option.cost_max && item.option.cost_max != null) {
           item.total_cost = item.option.cost_max;
         }
       }

       itemForm.patchValue({
         cost: this.roundTo(cost,2),
         duration: item.duration,
         quantity: item.quantity,
         total_cost: this.roundTo(item.total_cost,2),
         payer: item.payer,
       }, { emitEvent: false });
     }

     if (!item?.quantity) {//de-select quantity
       itemForm.patchValue({
         cost: this.roundTo(item.cost, 2),
         duration: item.duration,
         quantity: item.quantity,
         total_cost: this.roundTo(0, 2),
         payer: item.payer,
       }, { emitEvent: false });
     }
  }

  changeItemDuration(itemForm: AbstractControl): void {
    if (itemForm.get('daily')?.value) {
      const start = itemForm.get('start')?.value ? itemForm.get('start')?.value : this.form?.get('checkout_datetime')?.value;
      const startDate = new Date(start);
      const end = itemForm.get('end')?.value ? itemForm.get('end')?.value : this.form?.get('checkin_datetime')?.value;
      const endDate = new Date(end);
      const days = this.diffDays2(startDate, endDate);
      itemForm.get('duration')?.patchValue(days);
      this.changeTotalCost(itemForm);
    }
  }


  diffDays2(date: Date, otherDate: Date): number {
    let days = Math.floor((Date.UTC(date.getFullYear(), date.getMonth(), date.getDate()) - Date.UTC(otherDate.getFullYear(), otherDate.getMonth(), otherDate.getDate())) / (1000 * 60 * 60 * 24));
    return Math.abs(days) > 0 ? Math.abs(days) : 1;
  }

  saveItems(type: string, fee: string) {
    const fees = this.calcItems(type);
    this.form.get(fee).patchValue(fees);
    //console.log(type+' -type'+fee+' -fee');
    this.calculateDepts();
    // this.closeOutside = false;
    if (this.dialogRef) {
      this.dialogRef.close();
    }
    this.totalCalc();
    this.activateParentCalc();
  }

  calcItems(type?: string): number {
    const items = this.form.get('items')?.value.filter((item: IBookingItem) => {
      return item.option.option_type === type;
    }) ?? [];
    return items.length > 0 ? items.map((option: IBookingItem) => option.total_cost)
      .reduce((total_cost1: number, total_cost2: number) => this.roundTo(Number(total_cost1 ?? 0) + Number(total_cost2 ?? 0),2), 0) : 0;
  }


  calculateDepts() {
    this.summary_payers.driver.debt = this.calculateDept('driver');
    this.summary_payers.company.debt = this.calculateDept('company');
    this.summary_payers.agent.debt = this.calculateDept('agent');
  }

  calculateDept(payer: string) {
    const items = this.form.get('items')?.value.filter((item: IBookingItem) => {
      return item.payer === payer;
    }) ?? [];
    return items.length > 0 ? items.map((option: IBookingItem) => option.total_cost)
      .reduce((total_cost1: number, total_cost2: number) => this.roundTo(Number(total_cost1 ?? 0) + Number(total_cost2 ?? 0),2), 0) : 0;
  }


  cancelItems() {
    this.form.get('items')?.patchValue(this.itemsSnapshot, { emitEvent: false });
    this.form.get('items')?.markAsPristine();
    if (this.dialogRef) {
      this.dialogRef.close();
    }
  }

  roundTo(n: number, digits: number): number {//stackOverflow method to round numbers
    if (Number.isNaN(n)){
      console.warn('was nan: '+ n)
      return 1;
    }
    var negative = false;
    if (digits === undefined) {
      digits = 0;
    }
    if (n < 0) {
      negative = true;
      n = n * -1;
    }
    var multiplicator = Math.pow(10, digits);
    n = parseFloat((n * multiplicator).toFixed(11));
    n = Number((Math.round(n) / multiplicator).toFixed(digits));
    if (negative) {
      n = Number((n * -1).toFixed(digits));
    }
    return n;
  }

  // if(this.vat_fee_Ref.nativeElement.value!=this.form.get('vat_fee')?.value){
// console.log('some change vat');
//this.vat_feeCalc();
//this.totalCalc();
//this.activeParentCalc();
  //}


}
