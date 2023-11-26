import { AutocompleteSelectorComponent } from './../../_selectors/autocomplete-selector/autocomplete-selector.component';
import { DecimalPipe } from '@angular/common';
import { Component, ElementRef, EventEmitter, HostListener, Injector, OnDestroy, OnInit, Output, ViewChild } from '@angular/core';
import { AbstractControl, FormArray, FormGroup, Validators } from '@angular/forms';
import { MatDialog, MatDialogRef } from '@angular/material/dialog';
import { MatTable } from '@angular/material/table';
import { Router } from '@angular/router';
import { AbstractFormComponent } from 'src/app/abstract-form/abstract-form.component';
import { IAgent } from 'src/app/agent/agent.interface';
import { AgentService } from 'src/app/agent/agent.service';
import { IBookingItem } from 'src/app/booking-item/booking-item.interface';
import { IBookingSource } from 'src/app/booking-source/booking-source.interface';
import { BookingSourceService } from 'src/app/booking-source/booking-source.service';
import { IBrand } from 'src/app/brand/brand.interface';
import { BrandService } from 'src/app/brand/brand.service';
import { CompanyPreferencesService } from 'src/app/company_preferences/company.service';
import { DatetimepickerComponent } from 'src/app/datetimepicker/datetimepicker.component';
import { IDriver } from 'src/app/driver/driver.interface';
import { DriverService } from 'src/app/driver/driver.service';
import { FormDialogService } from 'src/app/form-dialog/form-dialog.service';
import { MyLoadingService } from 'src/app/my-loading/my-loading.service';
import { IOptionsCollection } from 'src/app/options/options-collection.interface';
import { IOptions } from 'src/app/options/options.interface';
import { OptionsService } from 'src/app/options/options.service';
import { CreatePaymentComponent } from 'src/app/payment/create-payment/create-payment.component';
import { EditPaymentComponent } from 'src/app/payment/edit-payment/edit-payment.component';
import { IPayment } from 'src/app/payment/payment.interface';
import { PrintCheckboxComponent } from 'src/app/print-checkbox/print-checkbox.component';
import { PrintCheckboxService } from 'src/app/print-checkbox/print-checkbox.service';
import { CreateBookingModalService } from 'src/app/quotes/create-booking-modal/create-booking-modal.service';
import { IPayers } from 'src/app/rental/rental-form/rental-form.component';
import { IRental } from 'src/app/rental/rental.interface';
import { IStation } from 'src/app/stations/station.interface';
import { StationService } from 'src/app/stations/station.service';
import { SubaccountService } from 'src/app/subaccount/subaccount.service';
import { SummaryChargesComponent } from 'src/app/summary-charges/summary-charges.component';
import { ITypes } from 'src/app/types/types.interface';
import { TypesService } from 'src/app/types/types.service';
import { IVehicle } from 'src/app/vehicle/vehicle.interface';
import { VehicleService } from 'src/app/vehicle/vehicle.service';
import { GetParams } from 'src/app/_interfaces/get-params.interface';
import { AbstractSelectorComponent } from 'src/app/_selectors/abstract-selector/abstract-selector.component';
import { AgentSelectorComponent } from 'src/app/_selectors/agent-selector/agent-selector.component';
import { CompanySelectorComponent } from 'src/app/_selectors/company-selector/company-selector.component';
import { DrCustSelectorComponent } from 'src/app/_selectors/dr-cust-selector/dr-cust-selector.component';
import { SelectorService } from 'src/app/_selectors/selector/selector.service';
import { TagSelectorComponent } from 'src/app/_selectors/tag-selector/tag-selector.component';
import { AuthService } from 'src/app/_services/auth.service';
import {CancelReasonComponent } from '../cancel-reason/cancel-reason.component';
import { CancelReasonService } from '../cancel-reason/cancel-reason.service';
import { CreateRentalModalComponent } from '../create-rental-modal/create-rental-modal.component';
import { PrintBookingService } from '../print-booking/print-booking.service';
import moment from 'moment';
import { TagService } from 'src/app/tag/tag.service';
import { take } from 'rxjs/internal/operators/take';
import { AbstractAutocompleteSelectorComponent } from 'src/app/_selectors/abstract-autocomplete-selector/abstract-autocomplete-selector.component';
import { MatInput } from '@angular/material/input';
import { BreadcrumbsService } from 'src/app/breadcrumbs/breadcrumbs.service';
import { GroupSelectorComponent } from 'src/app/_selectors/group-selector/group-selector.component';
import { delay } from 'rxjs/internal/operators/delay';
import { NotificationService } from 'src/app/_services/notification.service';
import { ISummaryCharges } from 'src/app/summary-charges/summary-charges.interface';
import { CompanyService } from 'src/app/company/company.service';
import { ICompany } from 'src/app/company/company.interface';
import { filter, Subject } from 'rxjs';
import { ProgramService } from 'src/app/program/program.service';
import { IProgram } from 'src/app/program/program.interface';
import { CombinedService } from 'src/app/home/combined.service';
import { ICompanyPreferences } from 'src/app/company_preferences/company.interface';
@Component({
  selector: 'app-booking-form',
  templateUrl: './booking-form.component.html',
  styleUrls: ['./booking-form.component.scss']
})
export class BookingFormComponent extends AbstractFormComponent implements OnInit, OnDestroy {

  sequence_number: string='';
  modification_number: string='';
  cancel_reason: string = '';

  afterDLbool: boolean = false;
  summary_options: ISummaryCharges;

  form = this.fb.group({
    id:[],//ok
    booking_id:[],//in case becomes rental
    tags: [],//for tags method
    created_at: [],//ok
    updated_at: [],//ok
    status: [],//ok
    duration: [],//ok
    flight: [],//ok
    agent_voucher: [],//ok
    confirmation_number: [],//ok
    modification_number: [],//ok
    sequence_number: [],// when created,ok

    user_id: [],//ok
    company_id: [],//ok
    type_id: [, Validators.required],//ok
    brand_id: [,Validators.required],//ok
    agent_id: [],//ok
    source_id: [,Validators.required],//ok
    program_id: [],//ok
    vehicle_id: [],//ok
    quote_id: [],//ok
    contact_information_id: [],//ok
    cancel_reason_id: [],//ok

    customer_id: [],//ok driver is customer
    customer_text: [],//ok
    customer_type: [],//ok
    customer_driver: [, Validators.required],//ok

    checkout_datetime: [],//ok
    checkout_station_id: [],//ok
    checkout_place_id: [],//ok
    checkout_place_text: [],//ok
    //checkout_station_fee: [],//ok
    checkout_comments: [],//ok

    checkin_datetime: [],//ok
    checkin_station_id: [],//ok
    checkin_place_id: [],//ok
    checkin_place_text: [],//ok
    //checkin_station_fee: [],//ok
    checkin_comments: [],//ok

    may_extend: [0,],//ok
    comments: [],//ok

    sub_account: [],//ok
    sub_account_type: [],//ok
    sub_account_id: [],//ok

    phone: [],//ok
    extra_day: [],//ok

    extension_rate: [],//ok
    excess: [],//ok

    checkout_place:[],//ok through method
    checkin_place: [],//ok through method

    customer: [],//ok through method

    payments: [[],],//ok through method
    documents: [],//ok through method
    rental:[],//ok through method

    plus: [],//plus day (not to upload)
    IamBooking:[],

    summary_charges: this.fb.group({
      payer: [[],],//constructed
      subcharges_fee: [],//ok
      rate: [],//ok
      manual_agreement: [],//ok
      charge_type_id: [,Validators.required],//ok
      distance: [],//ok
      distance_rate: [],//ok
      rental_fee: [],//ok
      transport_fee: [],//ok
      insurance_fee: [],//ok
      options_fee: [],//ok
      fuel_fee: [],//ok
      discount: [],//ok
      discount_fee: [],//calculated
      vat_included: [true,],//new on booking
      vat: [],//ok
      vat_fee: [],//ok
      voucher: [],//ok
      total_paid: [[],],//ok
      total_net: [[],],//ok
      total: [],//ok
      balance: [],//ok
      items: this.fb.array([]),//from method
    }),

    options: [],//to submit as summary_charges items
    convert:[false,]// when convert quote to booking to handle docs
  });


  types: Array<string> = [];
  itemsSnapshot!: FormArray;
  dialogRef!: MatDialogRef<any>;

  payers: IPayers = {
    driver: {
      id: null,
      text: '(Οδηγός)',
      name: '',
      debt: 0,
      paid: 0
    },
    company: {
      id: null,
      text: '(Εταιρεία)',
      name: '',
      debt: 0,
      paid: 0
    },
    agent: {
      id: null,
      text: '(Πράκτορας)',
      name: '',
      debt: 0,
      paid: 0
    },
  };

  payersCleared: IPayers = {
    driver: {
      id: null,
      text: '(Οδηγός)',
      name: '',
      debt: 0,
      paid: 0
    },
    company: {
      id: null,
      text: '(Εταιρεία)',
      name: '',
      debt: 0,
      paid: 0
    },
    agent: {
      id: null,
      text: '(Πράκτορας)',
      name: '',
      debt: 0,
      paid: 0
    },
  };

  programs: IProgram[] = [];
  program_id!: number;
  duration: number=1;
  readonly Number = Number;
  optionsItemsComplete: boolean = false;

  rental!: IRental;
  customUrl: string;
  @ViewChild('brand', { static: false }) brand_id_Ref: AbstractSelectorComponent<any>;
  @ViewChild('stationO', { static: false }) stationO_id_Ref: AbstractSelectorComponent<any>;
  @ViewChild('stationI', { static: false }) stationI_id_Ref: AbstractSelectorComponent<any>;

  @ViewChild('driver') driver!: DrCustSelectorComponent; // was DriverSelectorComponent
  @ViewChild(CompanySelectorComponent) company!: CompanySelectorComponent;
  @ViewChild(AgentSelectorComponent) agent!: AgentSelectorComponent;
  @ViewChild('deptsTable') deptsTable!: MatTable<any>;
  @ViewChild('paymentsTable') paymentsTable!: MatTable<any>;
  @ViewChild('tag', { static: false }) tag_Ref: TagSelectorComponent;
  itemsLoad = new Subject<boolean>();
  $itemsLoad = this.itemsLoad.asObservable();
  companyPrefData!: ICompanyPreferences;
  checkin_free_minutes!: number;//comPref to charge extra day
  @ViewChild(SummaryChargesComponent) summaryComp: SummaryChargesComponent;

  constructor(protected injector: Injector, protected optionsSrv: OptionsService<IOptionsCollection>,
    protected dialogSrv: MatDialog, protected decimalPipe: DecimalPipe,
    private formDialogSrv: FormDialogService, protected programSrv: ProgramService<IProgram>,
    public matDialog: MatDialog, protected driverSrv: DriverService<IDriver>,
    public selectorSrv: SelectorService, public agentSrv: AgentService<IAgent>,
    public authSrv: AuthService, public comPrefSrv: CompanyPreferencesService,public companySrv:CompanyService<ICompany>,
    public brandSrv: BrandService<IBrand>, public sourceSrv: BookingSourceService<IBookingSource>,
    public stationSrv: StationService<IStation>, public vehicleSrv: VehicleService<IVehicle>,
    public printBookingSrv: PrintBookingService, public typesSrv: TypesService<ITypes>,
    public cancelSrv: CancelReasonService, public createBookingSrv: CreateBookingModalService,
    private urlSrv: Router, protected printCheckboxSrv: PrintCheckboxService, public notificationSrv: NotificationService,
    public subAccSrv: SubaccountService, public loadSrv: MyLoadingService, public tagSrv: TagService
    , public breadcrumbsSrv: BreadcrumbsService, public combinedSrv: CombinedService,) {
    super(injector);
  }

  ngOnInit(): void {
    moment.locale('el');
    this.spinnerSrv.hideSpinner();
    this.loadSrv.loadingOn();
    super.ngOnInit();
    this.customUrl = this.urlSrv.url.split('/')[2];//for payments to know if are on create mode
    window.addEventListener('scroll', this.onWindowScroll, true);

    this.combinedSrv.getBookings().subscribe((res) => {
      this.checkin_free_minutes = res.companyPref[res.companyPref.length - 1].checkin_free_minutes;
      this.companyPrefData = res.companyPref[res.companyPref.length - 1];

      const optionsIds: Array<any> = this.form.get('summary_charges.items')?.value.map((option: any) => option.option_id) ?? [];

      this.types = [...new Set(res.options.map(item => item.option_type))];
      res.options = res.options.filter(s => !optionsIds.includes(s.id));

      for (const item of res.options) {
        this.addItemFromOption(item, !!item.default_on);
      }
      this.itemsLoad.next(true);
      this.optionsItemsComplete = true;

      this.programs = res.programs;
      this.source_id_Ref.selector.data = res.sources;
      this.group_Ref.selector.data = res.groups;
      this.brand_id_Ref.selector.data = res.brands;
      this.tag_Ref.tagOptions = res.tags;
      this.stationO_id_Ref.selector.data = res.stations;
      this.stationI_id_Ref.selector.data = res.stations;
      this.company.selector.data = res.company;
      this.driver.selector.options = res.drivers;
      this.vehicle_Ref.selector.data = res.vehicles;
    });

  }



  ngAfterViewInit() {
    // console.log(this.form.get('summary_charges.items'));
    this.form.controls.customer_driver.valueChanges.subscribe((res) => {
      this.driverEvent();
    });

    this.form.controls.summary_charges.valueChanges.subscribe((res) => {
      if(this.createBookingSrv.createBookSubject.getValue()!=null){
        this.form.controls.options.patchValue(this.nullifyOptionsId(this.form.get('summary_charges.items').value));
     //  this.form.controls.options.patchValue(this.form.get('summary_charges.items').value);
      }
      else{
        this.form.controls.options.patchValue(this.form.get('summary_charges.items').value);
      }
    });

    this.printBookingSrv.afterDataLoadSubject.pipe(filter((value) => value)).subscribe(res => {  //after data load runs one time
        this.form.controls.booking_id.patchValue(this.form.controls.id.value);
        this.printBookingSrv.afterDataLoadSubject.next(false);
        this.afterDLbool = true;

        this.program_id = this.form.controls.program_id.value;
        if (this.form.controls.program_id.value == null) {//program must have a value
          this.form.controls.program_id.patchValue(1);
        }

        if (this.form.controls.rental.value) {// if has rental then status can be only rental
          this.form.controls.status.patchValue('rental');
        }

      if (this.customUrl == 'create') {// create mode-- summary option are initiaized in convert and edit mode
        this.summary_options = this.form.controls.summary_charges.value;
      }

       this.patchOneByOne(this.summary_options.items);

      /*
code   option_type    id  translation
CRR   rental_charges  24  Μίσθωμα Οχήματος
ADM   rental_charges  25  Επιπλέον Χιλιόμετρα
ADR   rental_charges  26  Κόστος Παράτασης
*/
        this.tag_Ref.tagsOutput.subscribe(res => this.form.controls.tags.patchValue(res));
        //console.log(this.tag_Ref.tags);
        this.printBookingSrv.total_paidSubject.next(this.form.get('summary_charges.total_paid').value);//sent calculated payments
      if (+this.basicRentalEdit_Ref.value == 0 && this.customUrl != 'create' || +this.basicRentalEdit_Ref.value == 0 && this.form.controls.convert.value==true) {
          console.log('failed items');
          console.log(this.summary_options.items.at(this.findItemInit(24)).total_cost);
          this.basicRentalEdit_Ref.value = this.summary_options.items.at(this.findItemInit(24)).total_cost; //this.summary_options.items[0].total_cost;
          // this.form.controls.summary_charges.patchValue(this.summary_options);
          this.form.get('summary_charges.rental_fee').patchValue(
            this.summary_options.items.at(this.findItemInit(24)).total_cost +
            this.summary_options.items.at(this.findItemInit(25)).total_cost +
            this.summary_options.items.at(this.findItemInit(26)).total_cost
          );
        }

      this.checkAgentDeselect();
      this.checkGroupDeselect();

        this.form.markAsPristine();
        this.form.markAsUntouched();
        this.loadSrv.loadingOff();
        // this.seeValuesConsole();
    }); //after data load runs one time

  }



  activateCalcs() {// manual invoke checker
    /*
code   option_type    id  translation
CRR   rental_charges  24  Μίσθωμα Οχήματος
ADM   rental_charges  25  Επιπλέον Χιλιόμετρα
ADR   rental_charges  26  Κόστος Παράτασης
*/
    console.log('calcs runs');
    //transfered from Βασικό Μίσθωμα
    this.payersCollection();
    this.changeTotalCost(this.items.at(this.findItem(24)));
    this.changeTotalCost(this.items.at(this.findItem(25)));
    this.changeTotalCost(this.items.at(this.findItem(26)));
    this.saveItems('rental_charges', 'summary_charges.rental_fee');//also refreshes payments-charges

    this.printBookingSrv.total_paidSubject.next(this.form.get('summary_charges.total_paid').value);//sent calculated payments
   // this.loadSrv.loadingOff();
    this.form.markAsDirty();
    this.form.markAllAsTouched();
  }

  checkAgentDeselect() {// sychronize agent selector readonly for sub
    if (this.agent_id_Ref.selector.selectControl.value) {
      this.agentBool = true;// enable sub
    }
    else {
      this.agentBool = false; //disable sub
      this.sub_Ref.selector.selectControl.patchValue(null);
    }
  }

  checkGroupDeselect(){
    if (!this.group_Ref.selector.selectControl.value) {
      this.hasGroup = false;
      this.includeGroupPlates = [];//clear;
    }
    else {
      this.hasGroup = true;
      if (this.vehicleData) {
        this.includeGroupPlates = [this.vehicleData?.type_id];
      }
    }
  }

  afterModalCreate() { // for modal when creating new driver - company etc to activate methods \\
    if (this.selectorSrv.createNewObjDriver.getValue()) {
      // console.log('test new driver');
      this.driverEvent();
    }
    else if (this.selectorSrv.createNewObjSource.getValue()) {
      this.sourceEvent();
    }
    else if (this.selectorSrv.createNewObjAgent.getValue()) {
      this.agentEvent();
    }
    else if (this.selectorSrv.createNewObjCompany.getValue()) {
      this.companyEvent();
    }
  }

  ngOnDestroy() {
    console.log('ng-destroy');
    this.printBookingSrv.total_paidSubject.next(null);
    this.breadcrumbsSrv.TitleSubject.next(null);

    //selectors
    this.selectorSrv.searchDriver.next(null);
    this.selectorSrv.searchStation.next(null);
    this.selectorSrv.searchSource.next(null);
    this.selectorSrv.searchAgent.next(null);
    this.selectorSrv.searchSubAccount.next(null);
    this.selectorSrv.searchGroup.next(null);
    this.selectorSrv.searchVehicle.next(null);
    this.selectorSrv.searchCompany.next(null);

    //from convert quote
    this.createBookingSrv.createBookSubject.next(null);
    this.createBookingSrv.stationOutSub.next(null);
    this.createBookingSrv.stationInSub.next(null);
    this.createBookingSrv.driverSub.next(null);
    //this.createBookingSrv.vehicleSub.next(null);
    this.createBookingSrv.sourceSub.next(null);
    this.createBookingSrv.agentSub.next(null);
    this.createBookingSrv.typeSub.next(null);
    this.createBookingSrv.payersSub.next(null);
    this.createBookingSrv.summaryChargesSub.next(null);
    this.createBookingSrv.summaryChargesItemsSub.next(null);

    //new modal
    this.selectorSrv.createNewObjSource.next(false);
    this.selectorSrv.createNewObjAgent.next(false);
    this.selectorSrv.createNewObjCompany.next(false);
  }

  @HostListener('window:beforeunload') //<-- Do NOT put a semicolon here
  async destroy() {
    await this.ngOnDestroy();
  }

  nullifyOptionsId(array: IBookingItem[]): IBookingItem[] {
     array.forEach((item) => item.id = '');
    return array;
  }

  sorting(array: IBookingItem[]): IBookingItem[] {
    return array.sort((a, b) => a.option.order - b.option.order || +a.option.id - +b.option.id);
  }

  payersCollection() {
    this.payers = this.payersCleared;//clear payers
   // this.driver.dataLoaded.subscribe(res => {
    this.selectorSrv.searchDriver.subscribe(res => {
      let name = '';
      let id = null;
      if (res?.contact) {
        name = res.contact?.lastname+' '+res.contact?.firstname;
        id = res.id;
      }else{//user has put unregistered driver
        name = this.driver_Ref.selector.searchCtrl.value;
        id = null;
      }
      this.payers.driver.name = name;
      this.payers.driver.id = id;
    });

    //this.company.dataLoaded.subscribe(res => {
    this.selectorSrv.searchCompany.subscribe(res => {
      let name = '';
      let id = null;
      if (res && this.form.controls.company_id.value) {
        name = res.name;
        id = res.id;
      }
      this.payers.company.name = name;
      this.payers.company.id = id;
    });

   // this.agent.dataLoaded.subscribe(res => {// need to have company_id to be charged---------------------------
    this.selectorSrv.searchAgent.subscribe(res => {
      let name = '';
      let id = null;
      if (res?.company_id && this.form.controls.agent_id.value) {
        name = res.name;
        id = res.id;
      }
      this.payers.agent.name = name;
      this.payers.agent.id = id;
    });

    if (this.form.controls.program_id.value == 1 || this.form.controls.program_id.value == 2 || this.form.controls.program_id.value == 3) {
      // console.log("mpike agent");
    //  this.agent.dataLoaded.subscribe(res => {// need to have company_id to be charged---------------------------
      this.selectorSrv.searchAgent.subscribe(res => {
        let name = '';
        if (res?.company_id && this.form.controls.agent_id.value) {
          name = res.name;
        }
        this.payers.agent.name = '';// based on program exclude payer agent
        this.payers.agent.id = null;
      });
    }

    // console.log(this.payers);
  }

  addItem(item: IBookingItem): void {
    const group = this.fb.group({
      id: item.id,
      option: item.option,
      option_id: item.option_id,
      daily: item.daily,
      duration: item.duration,
      quantity: item.quantity,
      payer: item.payer,
      cost: item.cost,
      total_cost: item.total_cost,
      start: item.start,
      end: item.end
    });
    this.items.push(group);
  }

  addItemFromOption(option: IOptions, active:boolean): void {
    const total = active ? (option.active_daily_cost ? option.cost_daily *
      this.duration : option.cost_daily) : 0;
    const item: IBookingItem = {
      id: '',
      option: option,
      option_id: option.id,
      daily: option.active_daily_cost,
      duration: active ? this.duration : 1,
      quantity: active ? 1 : 0,
      payer: 'driver',
      cost: option.cost_daily ? option.cost_daily : 0,
      net_total: total / +(1 + (this.roundTo(this.form.get('summary_charges.vat').value / 100, 2))),
      total_cost: total,
      start: null,
      end: null,
    };
    this.addItem(item);
  }

  get items() {
    return this.form.get('summary_charges.items') as FormArray;
  }

  findItemInit(optionId: number) {//summary_options.items don't have option_id
    for (let i = 0; i < this.summary_options.items.length; i++) {
      if (+this.summary_options.items[i]?.option?.id == optionId) {
        return i;
      }
    }
  }


  findItem(optionId: number) {
    for (let i = 0; i < this.items.length; i++) {
      if (this.items.at(i).value?.option_id == optionId) {
        return i;
      }
    }
  }

  findItemByIdFromRes(arrayItem: AbstractControl): number {
    for (let i = 0; i < this.summary_options.items.length; i++) {
      if (this.summary_options.items.at(i)?.option?.id == arrayItem.value?.option_id) {
        return i
      }
    }
  }


  patchOneByOne(resItems: IBookingItem[]) {//summary_options.items don't have option_id
    console.log(this.items.value);
    for (let i = 0; i < this.items.length; i++) {
      //  console.log(this.summary_options.items[i]?.option?.id);
      //  console.log(this.findItemByIdFromArray(this.items.at(i)));
      let result = this.findItemByIdFromRes(this.items.at(i));
      //    console.log(this.items.at(i).value);
      this.items.at(i).patchValue(resItems[result]);
    }
    console.log(this.items.value);
  }


  @ViewChild('company', { static: true }) company_Ref: AbstractSelectorComponent<any>;
  selectedPayer(itemForm: AbstractControl) {
    const item = itemForm?.value;
    // console.log(this.company_Ref.selector.selectControl.value);
    if (!this.form.controls.company_id.value) {//case company is removed or not exists
      item.payer = 'driver';
      itemForm.patchValue({
        payer: item.payer
      }, { emitEvent: false });
    }
    else {//company exists
      item.payer = 'company';
      itemForm.patchValue({
        payer: item.payer
      }, { emitEvent: false });
    }
    let program = +this.form.controls.program_id.value;
    let voucherActive = false;
    console.log('programa ' + program);
    if (program > 3) {
      voucherActive = true;
    }
    //selector agent and agent has afm and voucher program
    if (this.form.controls.agent_id.value && this.payers.agent.name && voucherActive) {
      item.payer = 'agent';
      itemForm.patchValue({
        payer: item.payer
      }, { emitEvent: false });
    }

  }


  changeTotalCost(itemForm: AbstractControl): void {
    const item = itemForm?.value;
    /*
   code   option_type    id  translation
   CRR   rental_charges  24  Μίσθωμα Οχήματος
   ADM   rental_charges  25  Επιπλέον Χιλιόμετρα
   ADR   rental_charges  26  Κόστος Παράτασης
    */
    // console.log(item);
    if (+item?.option?.id ==24) {// check basicRental to match initial form values
      if (this.form.controls.duration.value > 0) {
        item.quantity = 1;
        item.duration = this.form.controls.duration.value;
      }
      if (item.quantity) {
        let cost = item.total_cost / item.quantity;
       // if (item.daily) {
          cost /= item.duration;
       // }
        itemForm.patchValue({
          cost: this.roundTo(cost,2),
          duration: item.duration,
          quantity: item.quantity,
          total_cost: this.roundTo(cost * item.duration,2),
          payer: item.payer,
        }, { emitEvent: false });
      }
      this.selectedPayer(itemForm);
    }

    else if (+item?.option?.id == 26) {// check extension_rate to match initial form values
      // if (this.form.controls.extension_rate.value > 0) {
      //   item.quantity = 1;
      //   item.duration = this.form.controls.duration.value;
      //   item.total_cost = this.form.controls.extension_rate.value;
      // }
      if (item.quantity) {
        let cost = item.total_cost / item.quantity;
        if (item.daily) {
          cost /= item.duration;
        }
        itemForm.patchValue({
          cost: this.roundTo(cost ,2) ,
          duration: item.duration,
          quantity: item.quantity,
          total_cost: this.roundTo(cost * item.duration,2),
        }, { emitEvent: false });
      }
      this.selectedPayer(itemForm);
    }

    else if (+item?.option?.id == 25) {// check distance_rate to match initial form values
      let freeKm = +this.form.get('summary_charges.distance').value;
      let overallKm = 0;
      if (overallKm > freeKm) {//this.form.get('summary_charges.distance_rate').value > 0 &&
        item.quantity = 1;
        item.duration = this.form.controls.duration.value;
        item.total_cost = this.form.get('summary_charges.distance_rate').value * (overallKm - freeKm);
      }
      if (item.quantity) {
        let cost = item.total_cost / item.quantity;
        if (item.daily) {
          cost /= item.duration;
        }
        itemForm.patchValue({
          cost: this.roundTo(cost,2),
          duration: item.duration,
          quantity: item.quantity,
          total_cost: this.roundTo(cost * item.duration,2)
        }, { emitEvent: false });
      }
      this.selectedPayer(itemForm);
    }

    // else if (item?.quantity) {// casual case
    //   item.duration = this.form.controls.duration.value;
    //   let cost = item.total_cost / item.quantity;
    //   if (item.daily) {
    //     cost /= item.duration;
    //   }
    //   itemForm.patchValue({
    //     cost: this.roundTo(cost,2),
    //     duration: item.duration,
    //     quantity: item.quantity,
    //     total_cost: this.roundTo(cost * item.duration,2),
    //     payer: item.payer,
    //   }, { emitEvent: false });
    // }

  }

  changeTotalCostDuration(itemForm: AbstractControl): void {
    const item = itemForm?.value;
    if (item?.option?.option_type != 'rental_charges') {//rental items are calculated above
      if (item?.quantity) {// when duration has changed
        item.duration = this.form.controls.duration.value;
        if (item.daily) {
          item.total_cost = this.roundTo(item.cost * item.duration, 2);

          if (item.option.cost_max != 0 && item.total_cost > item.option.cost_max && item.option.cost_max != null) {
            item.total_cost = item.option.cost_max;
          }

        }

        itemForm.patchValue({
          // cost: this.roundTo(cost, 2),
          duration: item.duration,
          // quantity: item.quantity,
          total_cost: item.total_cost,
          //payer: item.payer,
        }, { emitEvent: false });
      }
    }
  }


  diffDays(date: Date, otherDate: Date): number {
    let days = Math.ceil(Math.abs(date.getTime() - otherDate.getTime()) / 1000 / 60 / 60 / 24);
    return days > 0 ? days : 1;
  }

  diffDays2(date: Date, otherDate: Date):number{
    let days= Math.floor((Date.UTC(date.getFullYear(), date.getMonth(), date.getDate()) - Date.UTC(otherDate.getFullYear(), otherDate.getMonth(), otherDate.getDate())) / (1000 * 60 * 60 * 24));
    return Math.abs(days) > 0 ? Math.abs(days): 1;
  }


  changeDuration(duration: any) {
    this.summaryComp.rate_Ref.nativeElement.value = this.roundTo(+this.basicRentalEdit_Ref.value / +this.form.controls.duration.value, 2);// needed otherwise summary charges will activate rateHndler and increase total cost
    this.duration = Number(duration);
    const start = this.form.get('checkout_datetime')?.value;
    if (start) {
      const checkin_datetime = new Date(start);

      checkin_datetime.setTime(checkin_datetime.getTime() + duration * 3600 * 24 * 1000);
      this.form.get('checkin_datetime')?.patchValue(moment(checkin_datetime).format('YYYY-MM-DD HH:mm'));
      setTimeout(() => this.checkInDate.timepickerControl.patchValue(moment(checkin_datetime).format('HH:mm')), 500);
    }
    this.reCalcItemsDuration();
    this.activateCalcs();
  }


  @ViewChild('checkout_datetime', { static: true }) checkOutDate: DatetimepickerComponent;
  @ViewChild('checkin_datetime', { static: true }) checkInDate: DatetimepickerComponent;
  checkout_datetime = this.form.controls.checkout_datetime.value;
  checkin_datetime = this.form.controls.checkin_datetime.value;
  changeDate() {
    const start = this.form.get('checkout_datetime')?.value;
    const end = this.form.get('checkin_datetime')?.value;
    this.duration = this.diffDays2(new Date(start), new Date(end));
    this.form.get('duration').patchValue(this.duration);
    this.calcPlusday();
      // for (const itemForm of this.items.controls) {
      //   this.changeItemDuration(itemForm);
      // }
  }

  calculateDept(payer: string) {
    const items = this.form?.get('summary_charges.items')?.value.filter((item: IBookingItem) => {
      return item.payer === payer;
    }) ?? [];
    return items.length > 0 ? items.map((option: IBookingItem) => option.total_cost)
      .reduce((total_cost1: number, total_cost2: number) => this.roundTo(Number(total_cost1 ?? 0) + Number(total_cost2 ?? 0),2), 0) : 0;
  }

  calculateDeptInit(payer: string) {
    const items = this.summary_options.items.filter((item: IBookingItem) => {
      return item.payer === payer;
    }) ?? [];
    return items.length > 0 ? items.map((option: IBookingItem) => option.total_cost)
      .reduce((total_cost1: number, total_cost2: number) => this.roundTo(Number(total_cost1 ?? 0) + Number(total_cost2 ?? 0), 2), 0) : 0;
  }

  calculatePaid(payer: string) {
    if (this.form.get('payments')?.value) {
      const payments = this.form?.get('payments')?.value.filter((payment: IPayment) => {
        if (payment.payment_type != 'pre-auth' && payment.payment_type != 'refund-pre-auth' && payment.payment_type != 'Εγγύηση' && payment.payment_type != 'Επιστροφή Χρημάτων Εγγύησης') {
          return payment.payer.type === payer;
        }
      }) ?? [];
      return payments.length > 0 ? payments.map((payment: IPayment) => payment.amount)
        .reduce((amount1: number, amount2: number) => this.roundTo(Number(amount1 ?? 0) + Number(amount2 ?? 0),2), 0) : 0;
    }
  }

  calculateDepts() {
    this.payers.driver.debt = this.calculateDept('driver');
    this.payers.company.debt = this.calculateDept('company');
    this.payers.agent.debt = this.calculateDept('agent');
  }

  calculateDeptsInit() {
    this.payers.driver.debt = this.calculateDeptInit('driver');
    this.payers.company.debt = this.calculateDeptInit('company');
    this.payers.agent.debt = this.calculateDeptInit('agent');
  }

  calculatePayments() {
    this.payers.driver.paid = this.calculatePaid('driver');
    this.payers.company.paid = this.calculatePaid('company');
    this.payers.agent.paid = this.calculatePaid('agent');
    this.form.get('summary_charges.total_paid')?.patchValue(this.payers.driver.paid + this.payers.company.paid);//+ this.payers.agent.paid
  }

  saveItems(type: string, fee: string) {
    const fees = this.calcItems(type);
     console.log(fees + ' fees');
    this.form.get(fee)?.patchValue(fees);//rental fee
     console.log(this.form.get(fee).value + " fee");
    if (fees > 0) {
      this.calculateDepts();
      this.calculatePayments();
      //rate
      let rate = +this.form.controls.duration.value
      this.form?.get('summary_charges.rate').patchValue(this.roundTo(fees / rate, 2));
    }
  }

  saveItems2(type: string, fee: string) {
    const fees = this.calcItems(type);
    // console.log(fees + ' fees');
    if (fees > 0) {
      this.form.get(fee)?.patchValue(+fees);//rental fee
      // console.log(this.form.get(fee).value + " fee");
      this.calculateDepts();
      this.calculatePayments();
    }
  }

  saveItemsInit(type: string, fee: string) {
    const fees = this.calcItemsInit(type);
    // console.log(fees + ' fees');
    if (fees > 0) {
      this.form.get(fee)?.patchValue(+fees);//rental fee
      // console.log(this.form.get(fee).value + " fee");
      this.calculateDeptsInit();
      this.calculatePayments();
    }
  }


  calcItems(type?: string): number {
    const items = this.form?.get('summary_charges.items')?.value.filter((item: IBookingItem) => {
      return item.option.option_type === type;
    }) ?? [];
    return items.length > 0 ? items.map((option: IBookingItem) => option.total_cost)
      .reduce((total_cost1: number, total_cost2: number) => this.roundTo(Number(total_cost1 ?? 0) + Number(total_cost2 ?? 0),2), 0) : 0;
  }

  calcItemsInit(type?: string): number {
    const items = this.summary_options.items.filter((item: IBookingItem) => {
      return item.option.option_type === type;
    }) ?? [];
    return items.length > 0 ? items.map((option: IBookingItem) => option.total_cost)
      .reduce((total_cost1: number, total_cost2: number) => this.roundTo(Number(total_cost1 ?? 0) + Number(total_cost2 ?? 0), 2), 0) : 0;
  }


  normalizeValue(number: number): string {
    return this.decimalPipe.transform(number, '.2') ?? '0';
  }


  renderPaymentTables() {
    this.calculatePayments();
    this.paymentsTable.renderRows();
  }

//---------payments-----------------------------------//

  addPayment(type: string): void {
    this.formDialogSrv.showDialog(CreatePaymentComponent, {
      typePayment: type, rental_id: this.form.controls.id.value, rental: true, payers: this.payers, comesFromNew:'create',
      excess: this.form.get('excess').value, discount_fee: this.form.get('summary_charges.discount_fee').value
    }, false)
      .subscribe((payment: any | undefined) => {
        if (payment) {
          // console.log(payment);
          payment.payer = {
            type: payment.payer,//needed for frontend
          };
          let currentPayer: string = payment.payer.type;//after if of payment
          let prefix = 'App\\';
          // console.log(this.payers[payment.payer.type]);//driver-comp-agent
          if (this.payers[payment.payer.type].id == null) {
              this.notificationSrv.showErrorNotification('μη εγγεγραμμένος πελάτης');
            }
          payment.payer_id = this.payers[payment.payer.type].id;
          payment.payer_type = prefix + currentPayer[0].toUpperCase() + currentPayer.substring(1);
          payment.payment_type = type;
          payment.balance = this.calculatePaid(currentPayer) - this.calculateDept(currentPayer);
          const payments = this.form.get('payments')?.value ?? [];
          payments.push(payment);
          this.form.get('payments')?.patchValue(payments);
          this.renderPaymentTables();
          this.activateCalcs();
          // console.log(payment);
        }
      });
    this.formDialogSrv.dialogRef.afterOpened().subscribe(() => {
      this.formDialogSrv.dialogRef.componentInstance.component.instance.rental = true;
      this.formDialogSrv.dialogRef.componentInstance.component.instance.payers = this.payers;
    });
  }

  editPayment(element: IPayment): void {
    const payments = this.form.get('payments')?.value;
    const payment: any = Object.assign({}, element);
    let payerType = payment.payer.type;//for patch value payers
    this.formDialogSrv.showDialog(EditPaymentComponent, {
      object: payment, type: element.payment_type, rental: true, payers: this.payers, payerType: payerType, comesFromNew: 'create',
      excess: this.form.get('excess').value, discount_fee: this.form.get('summary_charges.discount_fee').value
    }, false)
      .subscribe((payment: any | undefined) => {
        if (payment) {
          const index = payments.indexOf(element);
          payment.payer = {
            type: payment.payer
          };
          payment.payment_type = element.payment_type;
          let prefix = 'App\\';
          let currentPayer: string = payment.payer.type;//after if of payment
          if (this.payers[payment.payer.type].id == null) {
            this.notificationSrv.showErrorNotification('μη εγγεγραμμένος πελάτης');
          }
          payment.payer_id = this.payers[payment.payer.type].id;
          payment.payer_type = prefix + currentPayer[0].toUpperCase() + currentPayer.substring(1);
          payment.balance = this.calculatePaid(currentPayer) - this.calculateDept(currentPayer);
          payments[index] = payment;
          this.form.get('payments')?.patchValue(payments);
          this.renderPaymentTables();
          this.activateCalcs();
        }
      });
    this.formDialogSrv.dialogRef.afterOpened().subscribe(() => {
      this.formDialogSrv.dialogRef.componentInstance.component.instance.rental = true;
      this.formDialogSrv.dialogRef.componentInstance.component.instance.payers = this.payers;
    });
  }

  deletePayment(element: IPayment): void {
    const payments = this.form.get('payments')?.value;
    const index = payments.indexOf(element);
    payments.splice(index, 1);
    this.form.get('payments')?.patchValue(payments);
    this.renderPaymentTables();
  }


  //------------------------------//

  ShowCheckbox() {
    this.printCheckboxSrv.arrayPrints = [];
    this.matDialog.open(PrintCheckboxComponent, {
      width: '30%',
      height: '40%',
      data: this.form.value,
      autoFocus: false
    });
  }

  reasonChange(){
    this.matDialog.open(CancelReasonComponent, {
      width: '20%',
      height: '30%',
      data: this.form.controls.cancel_reason_id.value,
      autoFocus: false
    }).afterClosed().subscribe(reason => {
      this.form.controls.cancel_reason_id.patchValue(this.cancelSrv.cancelSubject.getValue()); console.log(this.cancelSrv.cancelSubject.getValue());
      this.cancelSrv.getOne(this.cancelSrv.cancelSubject.getValue()).subscribe(res => { this.cancel_reason = res?.title; this.createBookingSrv.callSaveSubject.next(true); });
    });
  }

  createRental(){
    this.form.markAsPristine();
    this.matDialog.open(CreateRentalModalComponent, {
      width: '60%',
      height: '30%',
      data: {
      form: this.form,
      main:this.form.value,
      station_out: this.form.controls.checkout_station_id.value,
      station_in: this.form.controls.checkin_station_id.value,
      driver: { id: this.form.controls.customer_driver.value.id, name:this.form.controls.customer_driver.value.name ,phone:this.form.controls.phone.value},

      vehicle:this.vehicleData,
      source_id: this.form.controls.source_id.value,
      agent_id: this.form.controls.agent_id.value,
      type_id: this.form.controls.type_id.value,

      payers:this.payers,
      summaryCharges: this.form.controls.summary_charges.value,//is of type booking item and will be init in rental form init
      summaryChargesItems: this.form.get('summary_charges.items').value// items for summary
      },
      autoFocus: false
    });
  }

  //-------------status header---------------------//
  @Output() selectedStatus = new EventEmitter();

  StatusChange(status: string) {
    this.selectedStatus.emit(status);//from buttons change
    this.form.controls.status.patchValue(status);
    if (status == 'cancelled') {

    } else if (status == 'pending') {
      this.cancel_reason = null;
      this.form.controls.cancel_reason_id.patchValue(null);
      this.createBookingSrv.callSaveSubject.next(true);
    }
  }

  //----final increased form summary_charges---//

  finalIncreasedHandler(event: number) {// add the difference of final to rental_charges
    this.items?.at(this.findItem(24))?.get('total_cost').patchValue(this.roundTo(+this.items?.at(this.findItem(24))?.get('total_cost').value + Number(event),2));
    let total = this.roundTo(+this.items?.at(this.findItem(24))?.get('total_cost').value + Number(event),2);
    this.items?.at(this.findItem(24))?.get('cost').patchValue(this.roundTo(total / +this.form.controls.duration.value,2));
    this.activateCalcs();
  }

  rateHandler(event: number) {
    this.items?.at(this.findItem(24))?.get('cost').patchValue(this.roundTo(Number(event), 2));
    this.items?.at(this.findItem(24))?.get('total_cost').patchValue(this.roundTo(Number(event) * +this.form.controls.duration.value,2));
    this.activateCalcs();
    console.log(this.items.value);
  }

  activateParent(event: boolean) {
    this.activateCalcs();
  }

  //--------scroll-------------------//
  scroll(el: HTMLElement) {
    el.scrollIntoView({ behavior: 'smooth' });
  }

  onWindowScroll() {
    const scrollTopHeight = document.getElementById('myScroll').scrollTop;
    //this.showButton = true;
    let element = document.getElementById('scrolling');
    if (scrollTopHeight > 300) {
      // console.log(scrollTopHeight);
      element.style.display = 'block'
    } else if (element) {
      element.style.display = 'none'
      // console.log(scrollTopHeight);
    }
  }

  scrollTop(el: HTMLElement) {
    el.scrollIntoView({ behavior: 'smooth' });
    setTimeout(() => document.getElementById('myScroll').scrollTop = 0, 300);
  }

  //---------source auto agent include----------------//
  source_id: string;
  includeSourceAgent = [];
  @ViewChild('source', { static: true }) source_id_Ref: AbstractSelectorComponent<any>;
  sourceParams: GetParams = { filters: [] };
  sourceComplete: boolean = false;

  sourceEvent() {
    let array = [];
    this.selectorSrv.searchSource.subscribe(res => {
      this.source_id = res?.id;
      if (res?.brand_id) {
        this.form.controls.brand_id.patchValue(res?.brand_id);// based on source, brand is selected but can be altered by user
      }
      this.form.controls.source_id.patchValue(res?.id)
      this.includeSourceAgent = [];//clear
      if (res?.program_id) {
        this.form.controls.program_id.patchValue(res?.program_id);
      }
      this.agentSrv.get().subscribe(res => {
       // this.sourceParams.filters['include_id[]'].push('.');//first ini specific filter val //init with dot to stop all data come
        array = [0];
        res.data?.forEach((item) => {
          if (item.booking_source_id == this.source_id) {
            this.includeSourceAgent.push(item.id);
            //prepare agent filter
          //  this.sourceParams.filters['include_id[]'].push(item.id);
            array.push(item.id);
          };
        });
        this.sourceParams.filters['include_id[]'] = array;
        //nullify agent
        if (!array.includes(this.form.controls.agent_id.value)) {
          this.form.controls.agent_id.patchValue(null);
          this.agent_id_Ref.selector.data = [];
          this.agent_id_Ref.selector.selectControl.patchValue(null);
        }
        if (this.sourceParams.filters['include_id[]'].length == 0) {
         // this.sourceParams.filters['include_id[]'].splice(0, 1);
          this.sourceParams.filters['include_id[]'].push(0);
        }
        //fill the agent with options
        console.log(this.sourceParams.filters);
        this.agentSrv.get(this.sourceParams.filters, undefined, -1).subscribe(res => { this.agent_id_Ref.selector.data = res.data; this.sourceParams.filters['include_id[]'] = []; this.sourceComplete = true; });
        console.log(this.includeSourceAgent);
      });
    });
  }

  //-----------driver auto change phone--------------//

  driverData: any;
  customer_id: string;
  @ViewChild('driver', { static: false }) driver_Ref: AbstractAutocompleteSelectorComponent<any>;
  driverComplete: boolean = false;

   driverEvent() {
    console.log(this.driver_Ref.value)
    if (this.driver_Ref.value?.id!= null){
      this.driverSrv.edit(this.driver_Ref.value?.id).subscribe((res) => {
      this.selectorSrv.searchDriver.next(res); //selector must know driver for payers
      this.driverData = res;
      this.customer_id = res.id;
        if (this.driverData.contact.phone) {
          this.form.controls.phone.patchValue(this.driverData.contact.phone);
        } else {
          this.form.controls.phone.patchValue(this.driverData.contact.mobile);
        }
        if (this.driverComplete && this.afterDLbool) { //after save to not run, comes from db
          this.activateCalcs();
      }
        this.driverComplete = true;
      });
    }
    else{
      this.selectorSrv.searchDriver.next(null);
      if (this.driverComplete && this.afterDLbool) { //after save to not run, comes from db,this.afterDLbool cause in value changes runs twice
        this.activateCalcs();
      }
      this.driverComplete = true;
    }
  }

  //-----------company auto change for calcs--------------//
  company_id: string;
  companyComplete: boolean = false;
   companyEvent() {
    this.selectorSrv.searchCompany.subscribe(res => {
      this.company_id = res?.id;
      this.companyComplete = true;
    });
  }

  //---------station-out auto change place---------------//

  checkout_station_Data: any;
  checkout_station_id: string;
  includeOutPlaces = [];
  @ViewChild('placeO', { static: true }) placeO_id_Ref: AbstractAutocompleteSelectorComponent<any>;
  stationOutComplete: boolean = false;

  stationOutEvent() {
    this.selectorSrv.searchStation.subscribe(res => {
      this.checkout_station_Data = res;
      this.checkout_station_id = res?.id;
      this.includeOutPlaces = []//clear
      this.placeO_id_Ref.selector.options = [];
      res?.places.forEach((item) => {
        this.includeOutPlaces.push(item.id);
        this.placeO_id_Ref.selector.options.push(item);
       //filter places
      });
      //console.log(res.places);
      console.log(this.includeOutPlaces);
      this.form.controls.checkout_place.patchValue({ //choose first filtered place
        id: this.checkout_station_Data?.places[0]?.id,
        name: this.checkout_station_Data?.places[0]?.profiles?.el?.title
      });
      this.stationOutComplete = true;
    });
  }

  //---------station-in auto change place---------------//

  checkin_station_Data: any;
  checkin_station_id: string;
  includeInPlaces = [];
  @ViewChild('placeI', { static: true }) placeI_id_Ref: AbstractAutocompleteSelectorComponent<any>;
  stationInComplete: boolean = false;

  stationInEvent() {
    this.selectorSrv.searchStation.subscribe(res => {
      this.checkin_station_Data = res;
      this.checkin_station_id = res?.id;
      this.includeInPlaces = []//clear
      this.placeI_id_Ref.selector.options = [];
      res?.places.forEach((item) => {
        this.includeInPlaces.push(item.id);
        this.placeI_id_Ref.selector.options.push(item);
        //filter places
      });
      //console.log(res.places);
      //console.log(this.includeInPlaces);
      this.form.controls.checkin_place.patchValue({ //choose first filtered place
        id: this.checkin_station_Data?.places[0]?.id,
        name: this.checkin_station_Data?.places[0]?.profiles?.el?.title
      });
      this.stationInComplete = true;
    });
  }

  //-------agent_id and sub_account--------//
  agent_id: string;
  sub_account_id: string;
  sub_account_type: string;

  @ViewChild('agent', { static: true }) agent_id_Ref: AbstractSelectorComponent<any>;
  agentBool: boolean;
  includeAgentSub = [];
  agentComplete: boolean = false;

  agentEvent() {
    this.selectorSrv.searchAgent.subscribe(res => {
      this.agent_id = res?.id;
      this.form.controls.program_id.patchValue(res?.program_id);
      //console.log(this.agent_id_Ref.selector.selectControl.value + ' agent');
      //console.log(this.agentBool+ ' agent');
      this.includeAgentSub = [];//clear
      this.includeAgentSub = res?.sub_contacts;//filter sub_account
      if (this.includeAgentSub?.length==0) {
        this.includeAgentSub=[0];//if empty then zero= no sub accounts
      }
      if (!res?.sub_contacts.includes(this.form.controls.sub_account.value?.id)) {
        this.selectorSrv.searchSubAccount.next(null);
        this.sub_account_id = null;
        this.form.controls.sub_account.patchValue(null);
        this.sub_Ref.selector.selectControl.patchValue(null);
        this.sub_Ref.selector.data = [];
        this.form.controls.sub_account_id.patchValue(null);
        this.form.controls.sub_account_type.patchValue(null);
      }
      this.sourceParams.filters['include_id[]'] = [];//first ini specific filter val
      this.sourceParams.filters['include_id[]'] = this.includeAgentSub;
      this.subAccSrv.get(this.sourceParams.filters, undefined, -1).subscribe(res => { this.sub_Ref.selector.data = res.data; this.sourceParams.filters = { '': null }; this.agentComplete = true; });
      console.log(this.includeAgentSub);
      this.subAccountEvent();// must be initiated after agent event!!
    });
  }

  @ViewChild('sub', { static: true }) sub_Ref: AbstractSelectorComponent<any>;
  //subAccountComplete: boolean = false;

  subAccountEvent() {
    this.selectorSrv.searchSubAccount.subscribe(res => {
      if (!this.agent_id_Ref.selector.selectControl.value) {//nullify if no agent
        this.form.controls.sub_account_id.patchValue(null);
        this.form.controls.sub_account_type.patchValue(null);
        this.form.controls.sub_account.patchValue(null);
        this.sub_Ref.selector.data = [];
        this.sub_Ref.selector.selectControl.patchValue(null);
      }
       else {//there is agent
        this.sub_account_id = res?.id;
        this.form.controls.sub_account_id.patchValue(res?.id);
        this.form.controls.sub_account_type.patchValue(res?.type);
      }
     // this.subAccountComplete = true;
    });
  }

  //-----------group event to filter licence plates------//

  type_id!: string;
  @ViewChild('group', { static: true }) group_Ref: GroupSelectorComponent;
  includeGroupPlates = [];
  groupParams: GetParams = { filters: [] };
  groupComplete: boolean = false;

  groupEvent() {
    this.selectorSrv.searchGroup.subscribe(res => {
      this.type_id = res?.id;
      this.form.controls.type_id.patchValue(res?.id);
      this.form.controls.excess.patchValue(res?.excess);
     this.form.get('summary_charges.charge_type_id').patchValue(res?.id);
      console.log(res?.id + ' gr  ' + this.vehicleData?.type_id);
      if (res?.id != this.vehicleData?.type_id) {//check current vehicle is of this group,otherwise nullify
        this.vehicle_Ref.selector.selectControl.patchValue(null);
        this.vehicle_Ref.selector.data = [];
        this.vehicleData = null;
        this.vehicleDataDesc = '';
        this.selectorSrv.searchVehicle.next(null);
      }

      this.includeGroupPlates = [];//clear;
      this.includeGroupPlates.push(res?.id);
      // console.log(this.includeGroupPlates);//due to large array console not showing instantly correct
      this.groupParams.filters['type_id'] = [];//first ini specific filter val
      this.groupParams.filters['vehicle_status'] = [];//first ini specific filter val
      this.groupParams.filters['status2'] = [];//first ini specific filter val
      this.groupParams.filters['from'] = [];
      this.groupParams.filters['to'] = [];
      this.groupParams.filters['rental_id'] = [];
      //vehicle filters
      this.groupParams.filters['type_id'].push(res?.id);
      this.groupParams.filters['status2'].push('available');
      this.groupParams.filters['vehicle_status'].push('active');
      this.groupParams.filters['from'].push(this.checkout_datetime);
      this.groupParams.filters['to'].push(this.checkin_datetime);
      this.groupParams.filters['rental_id'].push(this.form.controls.id.value);
      //fill the vehicle with options
      console.log(this.groupParams.filters);
      this.vehicleSrv.get(this.groupParams.filters, undefined, -1).subscribe(res => { this.vehicle_Ref.selector.data = res.data; this.groupParams.filters = { '': null }; this.groupComplete = true; });
    });
  }

  //-----vehicle event that auto changes (optional the group-excess), certain the model-make-fuel-km---//
  vehicle_id: string;
  vehicleData: IVehicle;
  vehicleDataDesc: string='';
  @ViewChild('vehicle', { static: true }) vehicle_Ref: AbstractSelectorComponent<any>;
  hasGroup: boolean = false;
  vehicleComplete:boolean= false;

   vehicleEvent() {
    if (this.hasGroup) { //has group active
      this.selectorSrv.searchVehicle.subscribe(res => {
        this.vehicle_id = res?.id;
        this.form.controls.vehicle_id.patchValue(res?.id);
        this.vehicleData = res;//model-make
        this.vehicleDataDesc = this.vehicleData?.make +' ' + this.vehicleData?.model;
        this.vehicleComplete = true;
      });
    } else {// has not group
      this.selectorSrv.searchVehicle.subscribe(res => {
        this.vehicle_id = res?.id;
        this.form.controls.vehicle_id.patchValue(res?.id);
        this.vehicleData = res;//model-make
        this.vehicleDataDesc = this.vehicleData?.make + ' ' + this.vehicleData?.model;

        this.form.controls.type_id.patchValue(res?.type_id);//group-selector
        this.type_id = res?.type_id;// to not trigger group event
        this.form.get('summary_charges.charge_type_id').patchValue(res?.type_id);

        this.form.controls.excess.patchValue(res?.type.excess);

        this.includeGroupPlates = [];//clear;
        this.includeGroupPlates.push(res?.type_id);
        this.vehicle_Ref.selector.data = [];// clear after selected vehicle options after without group
        this.vehicleComplete = true;
      });
    }
  }

  //------Πληροφορίες διάρκειας-----//
  plusDayInitial: boolean = false;
  durationInitial: number = 1;
  daysDiff: any;
  calcPlusday() {
    let dateOut = new Date(this.form.controls.checkout_datetime.value);
    let dateIn = new Date(this.form.controls.checkin_datetime.value);
    let dayDifference = dateIn.getTime() - dateOut.getTime();
    this.daysDiff = dayDifference / (1000 * 3600 * 24);
    let days = this.diffDays2(dateOut, dateIn);
    let timeOut = dateOut.getHours() * 3600 + dateOut.getMinutes() * 60;
    let timeIn = dateIn.getHours() * 3600 + dateIn.getMinutes() * 60;
    const isLate = timeIn - timeOut - this.checkin_free_minutes * 60;
    // if (this.durationInitial == this.daysDiff) {
    //   this.plusDayInitial = false;
    //   this.form.get('duration').patchValue(days);//was same
    //   return;
    // }

    if (isLate > 1) {//same with daysDiff
      this.plusDayInitial = true;
      this.form.get('extra_day').patchValue(1);
      if (this.daysDiff <= 1) {
        this.plusDayInitial = false;
        this.form.get('extra_day').patchValue(0);
      }
    } else {
      this.plusDayInitial = false;
      this.form.get('extra_day').patchValue(0);
    }
    if (this.durationInitial < this.daysDiff) {//dates range increased

      if (this.form.get('extra_day').value) {
        setTimeout(() => this.form.controls.duration.patchValue(days + 1), 350);//was checked
        console.log('case1');//here
      } else {//to normal
        setTimeout(() => this.form.controls.duration.patchValue(days), 350);// was unchecked
        console.log('case2');//here
      }
    }
    else {// //dates range decreased

      if (this.form.get('extra_day').value) {
        setTimeout(() => this.form.controls.duration.patchValue(days + 1), 350);//was checked
        console.log('case3');//here
      } else {
        setTimeout(() => this.form.controls.duration.patchValue(days), 350);// was unchecked
        console.log('case4');//here
      }
    }

    console.log(this.daysDiff + ' daysDiff');//here
    console.log(this.form.controls.duration.value + ' duration');
    console.log(this.form.controls.extra_day.value + ' extra day');
    if (this.afterDLbool) {
      setTimeout(() => this.reCalcItemsDuration(), 500);
    }
  }

  calcPlusdayCheckbox() {
    let dateOut = new Date(this.form.controls.checkout_datetime.value);
    let dateIn = new Date(this.form.controls.checkin_datetime.value);
    let dayDifference = dateIn.getTime() - dateOut.getTime();
    this.daysDiff = dayDifference / (1000 * 3600 * 24);
    let days = this.diffDays2(dateOut, dateIn);
    if (this.durationInitial < this.daysDiff) {//dates range increased

      if (this.form.get('extra_day').value) {
        setTimeout(() => this.form.controls.duration.patchValue(days + 1), 350);//was checked
      } else {//to normal
        setTimeout(() => this.form.controls.duration.patchValue(days), 350);// was unchecked
      }
    }
    else {// //dates range decreased

      if (this.form.get('extra_day').value) {
        setTimeout(() => this.form.controls.duration.patchValue(days + 1), 350);//was checked
      } else {
        setTimeout(() => this.form.controls.duration.patchValue(days), 350);// was unchecked
      }
    }
    if (this.afterDLbool) {
      setTimeout(() => this.reCalcItemsDuration(), 500);
    }

  }

  reCalcItemsDuration() {
    console.log('recalc items duration');
    //let items = this.form.get('summary_charges.items').value;
    let items = this.items;
    console.log(this.items.value);
    for (let i = 0; i < this.items.length; i++) {
      this.changeTotalCostDuration(this.items.at(i));
    }
    console.log(items.value);
    this.saveItems2('insurances', 'summary_charges.insurance_fee');
    this.saveItems2('transport', 'summary_charges.transport_fee');
    this.saveItems2('extras', 'summary_charges.options_fee');
    this.activateCalcs();
  }


  mayExtend(){
    let val = this.form.controls.may_extend.value
      console.log(val);
    if (+val==1) {
      this.form.controls.may_extend.patchValue(1);
    }
    else{
      this.form.controls.may_extend.patchValue(0);
    }
  }

  timeChangeOut() {
    let dateOut = this.checkOutDate.value;
    let time: string = String(this.checkOutDate.timepickerControl.value);
    console.log(time);
    let dateIn = new Date(this.form.controls.checkin_datetime.value);
    let timeIn: string = String(this.checkInDate.timepickerControl.value);
    console.log(moment(dateOut).add(1, 'days').hour(+String(time).substring(0, 2)).minute(+String(time).substring(3, 6)).format('YYYY-MM-DD HH:mm'));
    this.form.controls.checkout_datetime.patchValue(moment(dateOut).hour(+String(time).substring(0, 2)).minute(+String(time).substring(3, 6)).format('YYYY-MM-DD HH:mm'));
    setTimeout(() => this.checkOutDate.timepickerControl.patchValue(moment(dateOut).hour(+String(time).substring(0, 2)).minute(+String(time).substring(3, 6)).format('HH:mm')), 200);

    if (this.customUrl == 'create' && new Date(dateOut) > new Date(dateIn)) {
      this.form.controls.checkin_datetime.patchValue(moment(dateOut).add(1, 'days').hour(+String(timeIn).substring(0, 2)).minute(+String(timeIn).substring(3, 6)).format('YYYY-MM-DD HH:mm'));
      setTimeout(() => this.checkInDate.timepickerControl.patchValue(moment(dateIn).hour(+String(timeIn).substring(0, 2)).minute(+String(timeIn).substring(3, 6)).format('HH:mm')), 200);
    }
    this.changeDate();
    // this.checkout_datetime = this.checkOutDate.datepickerControl.value;
    // console.log(event);
  }

  timeChangeIn() {
    let dateOut = this.checkOutDate.value;//check for time if less
    let timeOut: string = String(this.checkOutDate.timepickerControl.value);//check for time if less

    let dateIn = this.checkInDate.value;
    //if (this.checkInDate.datepickerControl.value != this.checkin_datetime) {
    let time: string = String(this.checkInDate.timepickerControl.value);
    console.log(dateIn);
    console.log(moment(dateIn).hour(+String(time).substring(0, 2)).minute(+String(time).substring(3, 6)).format('YYYY-MM-DD HH:mm'));
    this.form.controls.checkin_datetime.patchValue(moment(dateIn).hour(+String(time).substring(0, 2)).minute(+String(time).substring(3, 6)).format('YYYY-MM-DD HH:mm'));
    setTimeout(() => this.checkInDate.timepickerControl.patchValue(moment(dateIn).hour(+String(time).substring(0, 2)).minute(+String(time).substring(3, 6)).format('HH:mm')), 200);
    // this.form.controls.valid_date.patchValue(moment(dateIn).add(9, 'days').format('YYYY-MM-DD'));
    if (new Date(dateOut) > new Date(dateIn)) { // check if less
      this.form.controls.checkin_datetime.patchValue(moment(dateOut).hour(+String(timeOut).substring(0, 2)).minute(+String(timeOut).substring(3, 6)).format('YYYY-MM-DD HH:mm'));
      setTimeout(() => this.checkInDate.timepickerControl.patchValue(moment(dateOut).hour(+String(timeOut).substring(0, 2)).minute(+String(timeOut).substring(3, 6)).format('HH:mm')), 200);
    }
    this.changeDate();
   // }
    // this.checkin_datetime = this.checkInDate.datepickerControl.value;
    // console.log(event);
  }


  @ViewChild('basicEdit', { read: MatInput }) basicRentalEdit_Ref: MatInput;
  basicRentalEdit() {
    this.items?.at(this.findItem(24))?.get('total_cost').patchValue(this.roundTo(+this.basicRentalEdit_Ref.value,2));
    this.items?.at(this.findItem(24))?.get('cost').patchValue(this.roundTo(+this.basicRentalEdit_Ref.value / this.form.controls.duration.value, 2));
    this.activateCalcs();
  }

  ExtensionRate(){
    this.activateCalcs();
  }

  //----host listener for all auto changes------//

  @HostListener('document:click', ['$event'])
  EventClick(event: Event) {
    if (this.checkout_station_id != this.form.controls.checkout_station_id.value) {
      console.log('some click s out');
      //  console.log(this.checkout_station_id);
      //  console.log(this.form.controls.checkout_station_id.value + ' new' + this.checkout_station_id);
      this.stationOutEvent();
      this.form.markAsDirty();
      this.form.markAllAsTouched();
    }
    else if (this.checkin_station_id != this.form.controls.checkin_station_id.value) {
      console.log('some click s in');
      // console.log(this.checkout_station_id);
      // console.log(this.form.controls.checkout_station_id.value);
      this.stationInEvent();
      this.form.markAsDirty();
      this.form.markAllAsTouched();
    }
    // else if (this.customer_id != this.form.controls.customer_id.value) {// Οδηγός
    //   console.log('some click dr ');
    //   //console.log(this.driver_id);
    //   // console.log(this.form.controls.driver_id.value);
    //   this.driverEvent();
    // }
    else if (this.company_id != this.form.controls.company_id.value) { // Εταιρεία
      console.log('some click com ');
      //console.log(this.driver_id);
      // console.log(this.form.controls.driver_id.value);
      this.companyEvent();
      this.activateCalcs();
    }
    else if (this.source_id != this.form.controls.source_id.value) {//Πηγή
      console.log('some click src');
      // console.log(this.driver_id);
      //  console.log(this.form.controls.driver_id.value);
      this.sourceEvent();
      this.activateCalcs();
    }
    else if (this.agent_id != this.form.controls.agent_id.value) {//Συνεργάρτης-Πράκτορας
      console.log('some click a');
      // console.log(this.driver_id);
      //  console.log(this.form.controls.driver_id.value);
      this.agentEvent();
      this.activateCalcs();
    }
    else if (this.type_id != this.form.controls.type_id.value) {
      console.log('some click t');
      // console.log(this.driver_id);
      //  console.log(this.form.controls.driver_id.value);
      this.groupEvent();
      this.form.markAsDirty();
      this.form.markAllAsTouched();
    }
    else if (this.vehicle_id != this.form.controls.vehicle_id.value) {
      console.log('some click v');
      // console.log(this.driver_id);
      //  console.log(this.form.controls.driver_id.value);
      this.vehicleEvent();
      this.form.markAsDirty();
      this.form.markAllAsTouched();
    }
    else if (this.form.controls.program_id.value != this.program_id) {//program change
      console.log('some click pr');
      this.program_id = this.form.controls.program_id.value;
      this.activateCalcs();
    }
    else if (this.checkOutDate.datepickerControl.value != this.checkout_datetime) {
      console.log('some click date check out');
      this.timeChangeOut();
      this.checkout_datetime = this.checkOutDate.datepickerControl.value;
      this.activateCalcs();
    }
    else if (this.checkInDate.datepickerControl.value != this.checkin_datetime) {
      console.log('some click date check in');
      this.timeChangeIn();
      this.checkin_datetime = this.checkInDate.datepickerControl.value;
      this.activateCalcs();
    }

    if (!this.agent_id_Ref.selector.selectControl.value) {//nullify if no agent
      this.form.controls.sub_account_id.patchValue(null);
      this.form.controls.sub_account_type.patchValue(null);
      this.sub_Ref.selector.data = [];
      this.sub_Ref.selector.selectControl.patchValue(null);
    }

  //  this.checkAgentDeselect();
  //  this.checkGroupDeselect();

  }

  @HostListener('document:change', ['$event'])
  EventChange(event: Event) {
    if (this.checkout_station_id != this.form.controls.checkout_station_id.value) {
      console.log('some change s out');
      // console.log(this.checkout_station_id);
      // console.log(this.form.controls.checkout_station_id.value);
      this.stationOutEvent();
    }
    else if (this.checkin_station_id != this.form.controls.checkin_station_id.value) {
      console.log('some change s in');
      // console.log(this.checkout_station_id);
      // console.log(this.form.controls.checkout_station_id.value);
      this.stationInEvent();
    }
    // else if (this.customer_id != this.form.controls.customer_id.value) {//Οδηγός
    //   console.log('some change dr');
    //   // console.log(this.driver_id);
    //   // console.log(this.form.controls.driver_id.value);
    //   this.driverEvent();
    // }
    else if (this.company_id != this.form.controls.company_id.value) { // Εταιρεία
      console.log('some change com ');
      //console.log(this.driver_id);
      // console.log(this.form.controls.driver_id.value);
      this.companyEvent();
      this.activateCalcs();
    }
    else if (this.source_id != this.form.controls.source_id.value) {//Πηγή
      console.log('some change src');
      // console.log(this.driver_id);
      //  console.log(this.form.controls.driver_id.value);
      this.sourceEvent();
      this.activateCalcs();
    }
    else if (this.agent_id != this.form.controls.agent_id.value) {//Συνεργάρτης-Πράκτορας
      console.log('some change a');
      // console.log(this.driver_id);
      //  console.log(this.form.controls.driver_id.value);
      this.agentEvent();
      this.activateCalcs();
    }
    else if (this.type_id != this.form.controls.type_id.value) {
      console.log('some change t');
      // console.log(this.driver_id);
      //  console.log(this.form.controls.driver_id.value);
      this.groupEvent();
    }
    else if (this.vehicle_id != this.form.controls.vehicle_id.value) {
      console.log('some change v');
      // console.log(this.driver_id);
      //  console.log(this.form.controls.driver_id.value);
      this.vehicleEvent();
    }
    else if (this.form.controls.program_id.value != this.program_id) {//program change
      console.log('some change pr');
      this.program_id = this.form.controls.program_id.value;
      this.activateCalcs();
    }
    else if (this.checkOutDate.datepickerControl.value != this.checkout_datetime) {
      console.log('some change date check out');
      this.timeChangeOut();
      this.checkout_datetime = this.checkOutDate.datepickerControl.value;
      this.activateCalcs();
    }
    else if (this.checkInDate.datepickerControl.value != this.checkin_datetime) {
      console.log('some change date check in');
      this.timeChangeIn();
      this.checkin_datetime = this.checkInDate.datepickerControl.value;
      this.activateCalcs();
    }

    if (!this.agent_id_Ref.selector.selectControl.value) {//nullify if no agent
      this.form.controls.sub_account_id.patchValue(null);
      this.form.controls.sub_account_type.patchValue(null);
      this.sub_Ref.selector.data = [];
      this.sub_Ref.selector.selectControl.patchValue(null);
    }

    this.form.markAsDirty();
    this.form.markAllAsTouched();

  //  this.checkAgentDeselect();
  //  this.checkGroupDeselect();

  }


  onEnter(event: any) {
    event.target.value += '\n';
    event.preventDefault();
  }

  roundTo(n: number, digits: number): number {//stackOverflow method to round numbers
    if (Number.isNaN(n)) {
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


  seeValuesConsole() {
    console.log('checkout_station_id=' + this.checkin_station_id);
    console.log('checkin_station_id=' + this.checkin_station_id);
    console.log('customer_id=' + this.customer_id);
    //console.log('driver_id=' + this.formComponent.driver_id);
    console.log('company_id=' + this.company_id);
    console.log('source_id=' + this.source_id);
    console.log('agent_id=' + this.agent_id);
    console.log('sub_account_id=' + this.sub_account_id);
    console.log('type_id=' + this.type_id);
    console.log('vehicle_id=' + this.vehicle_id);
    console.log('program_id=' + this.program_id);
    console.log('checkout_datetime=' + this.checkout_datetime);
    console.log('checkin_datetime=' + this.checkin_datetime);
  }

  //-----------------init events for create -edit components, selectors are empty or slow to have res in the beginning

  sourceEventInit(id:string) {
    let array = [];
    this.sourceSrv.edit(id).subscribe(res => {
      this.selectorSrv.searchSource.next(res);
      if (!this.sourceComplete && this.createBookingSrv.createBookSubject.getValue() == null && this.customUrl=='create' ) { // after save to not run,db has them.and convert, but create pass
        this.source_id = res?.id;
        this.form.controls.brand_id.patchValue(res?.brand_id);// based on source, brand is selected but can be altered by user
        this.form.controls.source_id.patchValue(res?.id)
        this.form.controls.program_id.patchValue(res?.program_id);
      }
      this.includeSourceAgent = [];//clear
      this.agentSrv.get().subscribe(res => {
        // this.sourceParams.filters['include_id[]'].push('.');//first ini specific filter val //init with dot to stop all data come
        array = [0];
        res.data?.forEach((item) => {
          if (item.booking_source_id == this.source_id) {
            this.includeSourceAgent.push(item.id);
            //prepare agent filter
            //  this.sourceParams.filters['include_id[]'].push(item.id);
            array.push(item.id);
          };
        });
        this.sourceParams.filters['include_id[]'] = array;
        //nullify agent
        if (!array.includes(this.form.controls.agent_id.value)) {
          this.form.controls.agent_id.patchValue(null);
          this.agent_id_Ref.selector.data = [];
          this.agent_id_Ref.selector.selectControl.patchValue(null);
        }
        if (this.sourceParams.filters['include_id[]'].length == 0) {
          // this.sourceParams.filters['include_id[]'].splice(0, 1);
          this.sourceParams.filters['include_id[]'].push(0);
        }
        //fill the agent with options
        console.log(this.sourceParams.filters);
        this.agentSrv.get(this.sourceParams.filters, undefined, -1).subscribe(res => { this.agent_id_Ref.selector.data = res.data; this.sourceParams.filters['include_id[]'] = []; this.sourceComplete = true; });
        console.log(this.includeSourceAgent);
      });
    });
  }


  companyEventInit(id: string) {
    this.companySrv.edit(id).subscribe(res => {
      this.company_id = res?.id;
      this.selectorSrv.searchCompany.next(res);
      this.companyComplete = true;
    });
  }


  stationOutEventInit(id:string) {
    this.stationSrv.edit(id).subscribe(res => {
      this.checkout_station_Data = res;
      this.checkout_station_id = res?.id;
      this.includeOutPlaces = []//clear
      this.placeO_id_Ref.selector.options = [];
      res?.places.forEach((item) => {
        this.includeOutPlaces.push(item.id);
        this.placeO_id_Ref.selector.options.push(item);
        //filter places
      });
      //console.log(res.places);
      //console.log(this.includeOutPlaces);
      if (this.stationOutComplete && this.afterDLbool) {// after save to not run,db has them
        this.form.controls.checkout_place.patchValue({ //choose first filtered place
          id: this.checkout_station_Data?.places[0]?.id,
          name: this.checkout_station_Data?.places[0]?.profiles?.el?.title
        });
      }
      this.stationOutComplete = true;
    });
  }

  stationInEventInit(id:string) {
    this.stationSrv.edit(id).subscribe(res => {
      this.checkin_station_Data = res;
      this.checkin_station_id = res?.id;
      this.includeInPlaces = []//clear
      this.placeI_id_Ref.selector.options = [];
      res?.places.forEach((item) => {
        this.includeInPlaces.push(item.id);
        this.placeI_id_Ref.selector.options.push(item);
        //filter places
      });
      //console.log(res.places);
      //console.log(this.includeInPlaces);
      if (this.stationInComplete && this.afterDLbool) {// after save to not run,db has them
        this.form.controls.checkin_place.patchValue({ //choose first filtered place
          id: this.checkin_station_Data?.places[0]?.id,
          name: this.checkin_station_Data?.places[0]?.profiles?.el?.title
        });
      }
      this.stationInComplete = true;
    });
  }

  agentEventInit(id:string) {
    this.agentSrv.edit(id).subscribe(res => {
      this.selectorSrv.searchAgent.next(res);
      if (this.agentComplete && this.afterDLbool) {//after save they come from db
        this.agent_id = res?.id;
        this.form.controls.program_id.patchValue(res?.program_id);
      }
      //console.log(this.agent_id_Ref.selector.selectControl.value + ' agent');
      //console.log(this.agentBool+ ' agent');
      this.includeAgentSub = [];//clear
      this.includeAgentSub = res?.sub_contacts;//filter sub_account
      if (this.includeAgentSub?.length==0) {
        this.includeAgentSub = [0];//if empty then zero= no sub accounts
      }
      if (!res?.sub_contacts.includes(this.form.controls.sub_account.value?.id)) {
        this.selectorSrv.searchSubAccount.next(null);
        this.sub_account_id = null;
        this.form.controls.sub_account.patchValue(null);
        this.sub_Ref.selector.selectControl.patchValue(null);
        this.sub_Ref.selector.data = [];
        this.form.controls.sub_account_id.patchValue(null);
        this.form.controls.sub_account_type.patchValue(null);
      }
      this.sourceParams.filters['include_id[]'] = [];//first ini specific filter val
      this.sourceParams.filters['include_id[]'] = this.includeAgentSub;
      this.subAccSrv.get(this.sourceParams.filters, undefined, -1).subscribe(res => { this.sub_Ref.selector.data = res.data; this.sourceParams.filters = { '': null }; this.agentComplete = true; });
      console.log(this.includeAgentSub);
      this.subAccountEvent();// must be initiated after agent event!!
    });
  }


  groupEventInit(id:string) {
    this.typesSrv.edit(id).subscribe(res => {
      this.selectorSrv.searchGroup.next(res);
      if (this.groupComplete && this.afterDLbool) {// they come from db after save or convert
        this.type_id = res?.id;
        this.form.controls.type_id.patchValue(res?.id);
        this.form.controls.excess.patchValue(res?.excess);
        this.form.get('summary_charges.charge_type_id').patchValue(res?.id);
        console.log(res?.id + ' gr  ' + this.vehicleData?.type_id);
        if (res?.id != this.vehicleData?.type_id) {//check current vehicle is of this group,otherwise nullify
          this.vehicle_Ref.selector.selectControl.patchValue(null);
          this.vehicle_Ref.selector.data = [];
          this.vehicleData = null;
          this.vehicleDataDesc = '';
          this.selectorSrv.searchVehicle.next(null);
        }
      }
      this.includeGroupPlates = [];//clear;
      this.includeGroupPlates.push(res?.id);
      // console.log(this.includeGroupPlates);//due to large array console not showing instantly correct
      this.groupParams.filters['type_id'] = [];//first ini specific filter val
      this.groupParams.filters['vehicle_status'] = [];//first ini specific filter val
      this.groupParams.filters['status2'] = [];//first ini specific filter val
      this.groupParams.filters['from'] = [];
      this.groupParams.filters['to'] = [];
      this.groupParams.filters['rental_id'] = [];
      //vehicle filters
      this.groupParams.filters['type_id'].push(res?.id);
      this.groupParams.filters['status2'].push('available');
      this.groupParams.filters['vehicle_status'].push('active');
      this.groupParams.filters['from'].push(this.checkout_datetime);
      this.groupParams.filters['to'].push(this.checkin_datetime);
      this.groupParams.filters['rental_id'].push(this.form.controls.id.value);
      //fill the vehicle with options
      console.log(this.groupParams.filters);
      this.vehicleSrv.get(this.groupParams.filters, undefined, -1).subscribe(res => { this.vehicle_Ref.selector.data = res.data; this.groupParams.filters = { '': null }; this.groupComplete = true; });
    });
  }





}
