import { Component, Injector, Input, OnInit, HostListener, EventEmitter, Output, ViewChild } from '@angular/core';
import { AbstractFormComponent } from 'src/app/abstract-form/abstract-form.component';
import { PaymentMethodService } from '../payment-method.service';
import { IPaymentMethod } from '../payment-method.interface';
import { Validators } from '@angular/forms';
import { IPayers } from 'src/app/rental/rental-form/rental-form.component';
import { ActivatedRoute, Router } from '@angular/router';
import { CompanyPreferencesService } from 'src/app/company_preferences/company.service';
import { PaymentCardsService } from '../payment-cards.service';
import { AuthService } from 'src/app/_services/auth.service';
import { SelectorService } from 'src/app/_selectors/selector/selector.service';
import {MatDialogRef, MatDialog} from '@angular/material/dialog';
import { PrintCheckboxComponent } from 'src/app/print-checkbox/print-checkbox.component';
import { MyLoadingService } from 'src/app/my-loading/my-loading.service';
import { BrandService } from 'src/app/brand/brand.service';
import { IBrand } from 'src/app/brand/brand.interface';
import { AbstractSelectorComponent } from 'src/app/_selectors/abstract-selector/abstract-selector.component';
import { IStation } from 'src/app/stations/station.interface';
import { StationService } from 'src/app/stations/station.service';
import { AbstractAutocompleteSelectorComponent } from 'src/app/_selectors/abstract-autocomplete-selector/abstract-autocomplete-selector.component';
import { DatetimepickerComponent } from 'src/app/datetimepicker/datetimepicker.component';
import moment from 'moment';
import { NotificationService } from 'src/app/_services/notification.service';
import { MatSelect } from '@angular/material/select';
import { CombinedService } from 'src/app/home/combined.service';


@Component({
  selector: 'app-payment-form',
  templateUrl: './payment-form.component.html',
  styleUrls: ['./payment-form.component.scss']
})
export class PaymentFormComponent extends AbstractFormComponent implements OnInit {
  rental: boolean|null = null;
  payers!: IPayers;

  methods: IPaymentMethod[] = [];
  cards: IPaymentMethod[] = [];

  rentalData: any;
  rental_id: string;

  @ViewChild('brand', { static: false }) brand_id_Ref: AbstractSelectorComponent<any>;
  @ViewChild('station', { static: false }) station_id_Ref: AbstractSelectorComponent<any>;

  @ViewChild('customer', { static: false }) customer_id_Ref: AbstractSelectorComponent<any>;
  @ViewChild('rental', { static: false }) rental_id_Ref: AbstractSelectorComponent<any>;
  @ViewChild('user', { static: false }) user_id_Ref: AbstractSelectorComponent<any>;


  @ViewChild('datetime', { static: false }) datetime: DatetimepickerComponent;

  constructor(injector: Injector, public paymentCardsSrv: PaymentCardsService,
    public paymentMethodSrv: PaymentMethodService, public brandSrv: BrandService<IBrand>,
    public comPrefSrv: CompanyPreferencesService, public authSrv: AuthService,
    protected selectorSrv: SelectorService, protected matDialog: MatDialog, public combinedSrv: CombinedService,
    protected route: ActivatedRoute, public urlSrv: Router, public loadSrv: MyLoadingService,
    public stationSrv: StationService<IStation>,public notificationSrv: NotificationService){
    super(injector);
  }

  form = this.fb.group({
    id: [],
    payment_datetime: [null,Validators.required],
    payer: [, Validators.required],
    amount: [,Validators.required],
    rental_id: [],
    sequence_number:[],
    brand_id: [],
    user_id: [,Validators.required],
    station_id: [],
    place: [],
    payment_method: [],
    comments: [],

    documents:[],

  credit_card_number:[],
  credit_card_month:[],
  credit_card_month_year:[],

  credit_card_year:[],
  cheque_number:[],
  cheque_due_date:[],
  bank_transfer_account:[],
  card_type: [],
  foreigner:[],

  conInvoice:[],
  conRental:[],

  remaining:[0,],

  IamPayment:[]

  });

 // payerData!:any;
  currentDate = new Date();
  ngPay:string='';
  header: string;
  amountHeader: string;
  @Input() headerCreate:boolean = false;
  @Input() isFromNew: boolean = false;

  type!: string | null;

  conInvoice: any;
  conRental: any;
customUrl!: string;
isDialog: number|null = null;

  expriredCard=false;

  @ViewChild('payer') matRef: MatSelect;


  ngOnInit() {
    this.spinnerSrv.hideSpinner();
    this.loadSrv.loadingOn();
    super.ngOnInit();

    this.combinedSrv.getPayments().subscribe((res) => {
      this.brand_id_Ref.selector.data = res.brands;
      this.methods = res.getMethods;
      this.cards = res.getCards;
      this.station_id_Ref.selector.data = res.stations;
      this.rental_id_Ref.selector.data = res.rentals;
      if (this.customer_id_Ref){
        this.customer_id_Ref.selector.data = res.customers;
      }
      this.user_id_Ref.selector.data = res.users;

    });

  //  this.brandSrv.get({}, undefined, -1).subscribe(res => { this.brand_id_Ref.selector.data = res.data })
    //this.type = this.route.snapshot.paramMap.get('type');
    // this.customUrl = this.urlSrv.url.split('/')[2];
    // console.log(this.customUrl);
    this.handleHeader(this.type);

    // this.paymentMethodSrv.get().subscribe(res => {
    //  this.methods = res;
    // });

    // this.paymentCardsSrv.get().subscribe(res => {
    //   this.cards = res;
    // });

    // this.stationSrv.get({}, undefined, -1).subscribe(res => {
    //   this.station_id_Ref.selector.data = res.data;
    // });

   // console.log(this.form.controls.place.value);

    if(!this.urlSrv.url.includes('payments')){// not run in payments page
      setTimeout(() => this.matRef.options.first.select(), 1000);
    }

    setTimeout(() => this.loadSrv.loadingOff(), 5000);
  }


  getPay(){
    this.ngPay = this.form.controls.payment_method.value;
   // console.log(this.form.controls.payment_method);
  }

  parseDate(){
    let month:string = this.form.controls.credit_card_month_year.value.split('-');
     console.log('year '+month[0]);
    //   console.log(month[1]);

    console.log('month '+month);
    let year = month[0].substring(2, 4);
    let currYear = new Date().getFullYear();
    let currMonth = new Date().getMonth() + 1;


    year = '20' + year;

    console.log('month new ' + year);

    console.log(moment().year(+year).month(+month[1]-1).format('YYYY-MM'))

    this.form.controls.credit_card_month.patchValue(+month[1]+1);
    this.form.controls.credit_card_year.patchValue(year);

    console.log(+month[1]);

    setTimeout(() => {
      if (+year > 2000) {

        if (+currYear > +month[0]) {// users year smaller than today
          this.form.controls.credit_card_month_year.setErrors({ expired: true });
          this.expriredCard = true;
        } else if (+currMonth > +month[1] && currYear == +month[0]) {// users month smaller than today
          this.form.controls.credit_card_month_year.setErrors({ expired: true });
          this.expriredCard = true;
        }
        else {
          this.form.controls.credit_card_month_year.setErrors(null);
          this.expriredCard = false;
        }
        this.form.controls.credit_card_month_year.patchValue(moment().year(+year).month(+month[1]-1).format('YYYY-MM'));
        this.form.controls.credit_card_month_year.updateValueAndValidity({ onlySelf: true, emitEvent: true });
      }
    }, 500);

  }


  rentalEvent(){
    this.selectorSrv.searchRental.subscribe(res =>
    { this.rentalData = res;
      this.rental_id = res.id;
      this.form.controls.brand_id.patchValue(this.rentalData.brand_id);
       this.form.controls.station_id.patchValue(this.rentalData.checkout_station_id);
       this.form.controls.place.patchValue(this.rentalData.checkout_place);
      });
  }

  @HostListener('document:input', ['$event'])
  handleEventInput(event: Event) {
    if (this.rental_id != this.form.controls.rental_id.value) {
      console.log('some input ');
      this.rentalEvent();
    }
  }

  @HostListener('document:click', ['$event'])
  handleEventClick(event: Event) {
    if (this.rental_id != this.form.controls.rental_id.value) {
      console.log('some click ');
      console.log(this.rental_id);
      console.log(this.form.controls.rental_id.value);
      this.rentalEvent();
    }
  }

  @HostListener('document:keypress', ['$event'])
  handleKeyboardEvent(event: KeyboardEvent) {
     // if (event.keyCode === 13) {
    //  // this.filterSearch();
    // }
    if (this.rental_id != this.form.controls.rental_id.value) {
      console.log('some keypress ');
      this.rentalEvent();
    }
  }

  @HostListener('document:change', ['$event'])
  handleEventChange(event: Event) {
    if (this.rental_id != this.form.controls.rental_id.value) {
      console.log('some change ');
      this.rentalEvent();
    }
  }

  ShowCheckbox(){
    this.matDialog.open(PrintCheckboxComponent, {
      width: '30%',
      height: '30%',
      data: this.form.value,
      autoFocus: false
    });
  }

// ShowPrint(){
//   this.matDialog.open(PrintPaymentComponent, {
//     width: '70%',
//     height: '70%',
//     data:Number(this.form.controls.id.value),
//     autoFocus: false
//   });
// }


  //---------station auto change place---------------//

  station_Data: any;
  station_id: string;
  includePlaces = [];
  @ViewChild('place', { static: true }) place_id_Ref: AbstractAutocompleteSelectorComponent<any>;

  stationEvent() {
    this.selectorSrv.searchStation.subscribe(res => {
      this.station_Data = res;
      this.station_id = res?.id;
      this.includePlaces = []//clear
      this.place_id_Ref.selector.options = [];
      res?.places.forEach((item) => {
        this.includePlaces.push(item.id);
        this.place_id_Ref.selector.options.push(item);
        //filter places
      });
      //console.log(res.places);
      console.log(this.includePlaces);
      this.form.controls.place.patchValue({ //choose first filtered place
        id: this.station_Data?.places[0]?.id,
        name: this.station_Data?.places[0]?.profiles?.el?.title
      });
    });
  }

  @HostListener('document:click', ['$event'])
  EventClick(event: Event) {
    if (this.station_id != this.form.controls.station_id.value) {
      console.log('some click s');
      //  console.log(this.checkout_station_id);
      //  console.log(this.form.controls.checkout_station_id.value + ' new' + this.checkout_station_id);
      this.stationEvent();
    }
    console.log(this.isFromNew);
  }

  @HostListener('document:change', ['$event'])
  EventChange(event: Event) {
    if (this.station_id != this.form.controls.station_id.value) {
      console.log('some change s');
      //  console.log(this.checkout_station_id);
      //  console.log(this.form.controls.checkout_station_id.value + ' new' + this.checkout_station_id);
      this.stationEvent();
    }
  }
  //------//

  handleHeader(header: string) {
    if(this.headerCreate){
      return this.handleHeaderCreate(header);
    }

    if (header == 'payment') {
      this.header = 'Επεξεργασία Είσπραξης';
      this.amountHeader = 'Ποσό Είσπραξης';
    }
    else if (header == 'refund') {
      this.header = 'Επεξεργασία Επιστροφής Χρημάτων';
      this.amountHeader = 'Ποσό Επιστροφής';

    } else if (header == 'pre-auth') {
      this.header = 'Επεξεργασία Εγγύησης';
      this.amountHeader = 'Ποσό Εγγύησης';
    }
    else {//refund-pre-auth
      this.header = 'Επεξεργασία Επιστροφής Χρημάτων Εγγύησης'
      this.amountHeader = 'Ποσό Επιστροφής Εγγύησης';
    }
  }

  handleHeaderCreate(header: string) {
    if (header == 'payment') {
      this.header = 'Προσθήκη Είσπραξης'
      this.amountHeader = 'Ποσό Είσπραξης';
    }
    else if (header == 'refund') {
      this.header = 'Προσθήκη Επιστροφής Χρημάτων'
      this.amountHeader = 'Ποσό Επιστροφής';
    } else if (header == 'pre-auth') {
      this.header = 'Προσθήκη Εγγύησης'
      this.amountHeader = 'Ποσό Εγγύησης';
    }
    else {//refund-pre-auth
      this.header = 'Προσθήκη Επιστροφής Χρημάτων Εγγύησης'
      this.amountHeader = 'Ποσό Επιστροφής Εγγύησης';
    }
  }

  @Output() amountPayment = new EventEmitter<string>();
  @Output() amountRefund = new EventEmitter<string>();
  @Output() amountPreAuth = new EventEmitter<string>();
  @Output() amountRefundPreAuth = new EventEmitter<string>();
  selectedPayer(payerType:string){
    //console.log(payerType);
    if(this.type=='payment'){
     // console.log(payerType);
      this.amountPayment.emit(payerType);
    }
   else if (this.type == 'refund') {
      // console.log(payerType);
      this.amountRefund.emit(payerType);
    }
    else if (this.type == 'pre-auth'){
      // console.log(payerType);
      this.amountPreAuth.emit(payerType);
    }
    else if (this.type == 'refund-pre-auth') {
      // console.log(payerType);
      this.amountRefundPreAuth.emit(payerType);
    }
  }





}
