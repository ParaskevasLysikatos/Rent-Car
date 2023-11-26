import { Component, Injector, OnInit, ViewChild } from '@angular/core';
import { FormArray, FormControl, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { IBrand } from 'src/app/brand/brand.interface';
import { CompanyPreferencesService } from 'src/app/company_preferences/company.service';
import { MatDialogRef, MatDialog } from '@angular/material/dialog';
import { PrintCheckboxComponent } from 'src/app/print-checkbox/print-checkbox.component';
import { PrintInvoiceService } from '../invoice-print/print-invoice.service';
import { MyLoadingService } from 'src/app/my-loading/my-loading.service';
import { AbstractFormComponent } from 'src/app/abstract-form/abstract-form.component';
import { BrandService } from 'src/app/brand/brand.service';
import { AbstractSelectorComponent } from 'src/app/_selectors/abstract-selector/abstract-selector.component';
import { IStation } from 'src/app/stations/station.interface';
import { StationService } from 'src/app/stations/station.service';
import { NotificationService } from 'src/app/_services/notification.service';
import { CombinedService } from 'src/app/home/combined.service';

@Component({
  selector: 'app-invoices-form',
  templateUrl: './invoices-form.component.html',
  styleUrls: ['./invoices-form.component.scss']
})
export class InvoicesFormComponent extends AbstractFormComponent implements OnInit {
  form = this.fb.group({
    id: [],
    type: [],
    range:  [],
    date:  [,Validators.required],
    fpa:  [],
    discount:  [],
    datePayed:  [],
    notes:  [],
    payment_way:  [],
    invoicee_type:  [],
    invoicee_id: [],
    invoicee: [, Validators.required],
    sub_discount_total:  [],
    fpa_perc:  [],
    final_fpa:  [],
    final_total:  [,Validators.required],
    brand_id:  [],
    station_id:  [],
    sequence_number: [],
    total:  [],
    rental_id:  [],
    sent_to_aade:  [],
    instance: [],
   items:[],
   payment_id:[],
    checkPrint:[],
    IamInvoice:[],

    rows:this.fb.array([])//create
  });

  //instanceData!:IInstance;
  brandData!: IBrand;
  rental_id!: string;
  Items!:any[];

  paymentsData!:any[];
  checkUrl: boolean = true;
  read:boolean=true;
  @ViewChild('brand', { static: false }) brand_id_Ref: AbstractSelectorComponent<any>;
  @ViewChild('stationF', { static: false }) stationF_id_Ref: AbstractSelectorComponent<any>;
  @ViewChild('rental', { static: false }) rental_id_Ref: AbstractSelectorComponent<any>;
  @ViewChild('customer', { static: false }) customer_id_Ref: AbstractSelectorComponent<any>;
  @ViewChild('payment', { static: false }) payment_id_Ref: AbstractSelectorComponent<any>;

  constructor(protected injector: Injector, private route: ActivatedRoute, public comPrefSrv: CompanyPreferencesService,
    public matDialog: MatDialog, public printInvSrv: PrintInvoiceService, public stationSrv: StationService<IStation>,
    public loadSrv: MyLoadingService, public urlSrv: Router, public combinedSrv: CombinedService,
     public brandSrv: BrandService<IBrand>,private notificationSrv: NotificationService) {
    super(injector);

  }

  ngOnInit(){
    this.spinnerSrv.hideSpinner();
  this.loadSrv.loadingOn();
    this.combinedSrv.getInvoices().subscribe((res) => {
      if (this.brand_id_Ref) {
        this.brand_id_Ref.selector.data = res.brands;
      }
      if (this.stationF_id_Ref) {
        this.stationF_id_Ref.selector.data = res.stations;
      }
      if (this.rental_id_Ref) {
        this.rental_id_Ref.selector.data = res.rentals;
      }
      if (this.customer_id_Ref) {
        this.customer_id_Ref.selector.data = res.customers;
      }
      this.payment_id_Ref.selector.data = res.payments;
    });

    //this.brandSrv.get({}, undefined, -1).subscribe(res => { this.brand_id_Ref.selector.data = res.data });

    // this.stationSrv.get({}, undefined, -1).subscribe(res => {
    //   this.stationF_id_Ref.selector.data = res.data;
    // });

  //   console.log(this.route.snapshot.routeConfig?.path);
     if(this.route.snapshot.routeConfig?.path=='create'){
      this.checkUrl = false;
      }

    setTimeout(() => this.loadSrv.loadingOff(), 5000);
  }


  //for create

rows(): FormArray {
  return this.form.get("rows") as FormArray
}


newRow(): FormGroup {
  return this.fb.group({
    code: null,
      title: null,
      price:null,
       quantity:1,
       total:null,
  })
}

  ShowCheckbox() {
    this.matDialog.open(PrintCheckboxComponent, {
      width: '30%',
      height: '30%',
      data: this.form.value,
      autoFocus: false
    });
  }

  BadPrint(evt: Event){
    evt.preventDefault();
    this.printInvSrv.badPrint(this.form.value).subscribe((res) =>
     { this.notificationSrv.showSuccessNotification('κακέκτυπο σβήστηκε επιτυχώς') },
     (err) => { this.notificationSrv.showErrorNotification('αποτυχία') });
  }

}
