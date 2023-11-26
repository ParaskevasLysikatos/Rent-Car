import { Component, HostListener, Injector, TemplateRef, ViewChild } from '@angular/core';
import { MatOption } from '@angular/material/core';
import { MatInput } from '@angular/material/input';
import { MatSelect } from '@angular/material/select';
import { IAgent } from 'src/app/agent/agent.interface';
import { AgentService } from 'src/app/agent/agent.service';
import { IBookingSource } from 'src/app/booking-source/booking-source.interface';
import { BookingSourceService } from 'src/app/booking-source/booking-source.service';
import { ICancelReasons } from 'src/app/booking/cancel-reason/cancel-reason.interface';
import { CancelReasonService } from 'src/app/booking/cancel-reason/cancel-reason.service';
import { PreviewComponent } from 'src/app/preview/preview.component';
import { IStation } from 'src/app/stations/station.interface';
import { StationService } from 'src/app/stations/station.service';
import { SubaccountService } from 'src/app/subaccount/subaccount.service';
import { ITypes } from 'src/app/types/types.interface';
import { TypesService } from 'src/app/types/types.service';
import { GetParams } from 'src/app/_interfaces/get-params.interface';
import { AbstractSelectorComponent } from 'src/app/_selectors/abstract-selector/abstract-selector.component';
import { SelectorService } from 'src/app/_selectors/selector/selector.service';
import { ApiService } from 'src/app/_services/api-service.service';
import { QuoteTotalService } from '../quote-total.service';
import { IQuotesCollection } from '../quotes-collection.interface';
import { QuotesService } from '../quotes.service';

@Component({
  selector: 'app-preview-quotes',
  templateUrl: './preview-quotes.component.html',
  styleUrls: ['./preview-quotes.component.scss'],
  providers: [{provide: ApiService, useClass: QuotesService}]
})
export class PreviewQuotesComponent extends PreviewComponent<IQuotesCollection> {
  displayedColumns = ['sequence_number', 'group', 'status', 'station_checkin',
    'checkin_place', 'checkin_datetime', 'duration', 'station_checkout', 'checkout_place', 'checkout_datetime',
    'customer', 'phone','booking_source_agent','actions'];

  @ViewChild('group_filter', { static: true }) groupFilter: TemplateRef<any>;

  @ViewChild('station_in_filter', { static: true }) stationInFilter: TemplateRef<any>;
  @ViewChild('date_from_in_filter', { static: true }) dateFromInFilter: TemplateRef<any>;
  @ViewChild('date_to_in_filter', { static: true }) dateToInFilter: TemplateRef<any>;
  @ViewChild('place_in_filter', { static: true }) placeInFilter: TemplateRef<any>;
  @ViewChild('phone_filter', { static: true }) phoneFilter: TemplateRef<any>;

  @ViewChild('station_out_filter', { static: true }) stationOutFilter: TemplateRef<any>;
  @ViewChild('date_from_out_filter', { static: true }) dateFromOutFilter: TemplateRef<any>;
  @ViewChild('date_to_out_filter', { static: true }) dateToOutFilter: TemplateRef<any>;

  @ViewChild('date_from_at_filter', { static: true }) dateFromAtFilter: TemplateRef<any>;
  @ViewChild('date_to_at_filter', { static: true }) dateToAtFilter: TemplateRef<any>;

  @ViewChild('cancel_reason_filter', { static: true }) cancelReasonFilter: TemplateRef<any>;

  @ViewChild('matRef') matRef: MatSelect;
  @ViewChild('matRef2') matRef2: MatSelect;
  @ViewChild('matRef3') matRef3: MatSelect;

  @ViewChild('payer_filter', { static: true }) payerFilter: TemplateRef<any>;

  @ViewChild('agent_filter', { static: true }) agentFilter: TemplateRef<any>;
  @ViewChild('source_filter', { static: true }) sourceFilter: TemplateRef<any>;
  @ViewChild('status_filter', { static: true }) statusFilter: TemplateRef<any>;

  //clear dates
  @ViewChild('input1', { read: MatInput }) input1: MatInput;
  @ViewChild('input2', { read: MatInput }) input2: MatInput;
  @ViewChild('input3', { read: MatInput }) input3: MatInput;
  @ViewChild('input4', { read: MatInput }) input4: MatInput;
  @ViewChild('input5', { read: MatInput }) input5: MatInput;
  @ViewChild('input6', { read: MatInput }) input6: MatInput;

  @ViewChild('stationO', { static: false }) stationO_id_Ref: AbstractSelectorComponent<any>;
  @ViewChild('stationI', { static: false }) stationI_id_Ref: AbstractSelectorComponent<any>;

  constructor(protected injector: Injector, protected typesSrv: TypesService<ITypes>, public sourceSrv: BookingSourceService<IBookingSource>
    , public totalQuotesSrv: QuoteTotalService, protected selectorSrv: SelectorService,
    public agentSrv: AgentService<IAgent>, public stationSrv: StationService<IStation>, protected cancelSrv: CancelReasonService  ) {
    super(injector);
  }

  groups!: ITypes[];
  cancels: ICancelReasons[] = [];

  ngOnInit() {
    super.ngOnInit();

    this.cancelSrv.get().subscribe(res => {
      this.cancels = res['data'];
    });

    this.sourceSrv.get({}, undefined, -1).subscribe((res) => { // fill the source selector with options
      this.source_id_Ref.selector.data = res.data;
    });

    this.typesSrv.get({}, undefined, -1).subscribe((res) => { // fill the group selector with options
      this.groups = res.data;
    });

    this.stationSrv.get({}, undefined, -1).subscribe(res => {
      this.stationO_id_Ref.selector.data = res.data;
      this.stationI_id_Ref.selector.data = res.data;
    });

    this.columns = [
      {
        columnDef: 'sequence_number',
        header: 'QUO#',
        cell: (element: IQuotesCollection) => `${element.sequence_number}`,
        sortBy: 'sequence_number',
        hasFilter: true
      },
      {
        columnDef: 'group',
        header: 'Group',
        cell: (element: IQuotesCollection) => `${element.type?.category.profiles?.el?.title}`,
        sortBy: 'type.category.current_profile.title',
        hasFilter: true,
        filterTemplate: this.groupFilter,
      },
      {
        columnDef: 'checkin_datetime',
        header: 'Ημ/νία Παραλαβής',
        cell: (element: IQuotesCollection) => `${this.changeDateFormat(element.checkin_datetime.substring(0, 16))}`,
        sortBy: 'checkin_datetime'
      },
      {
        columnDef: 'checkin_place',
        header: 'Τόπος Παραλαβής',
        cell: (element: IQuotesCollection) => `${element.checkin_place?.name}`,
        sortBy: 'checkin_place_text',
        hasFilter: true,
        filterTemplate: this.placeInFilter,
        filterField: 'checkin_place_id'
      },
      {//date filters and station in-----------------
        columnDef: 'station_checkin',
        header: 'Σταθμός Παραλαβής',
        cell: (element: IQuotesCollection) => `${element.station_checkin?.title}`,
        sortBy: 'checkin_station.current_profile.title',
        hasFilter: true,
        filterTemplate: this.stationInFilter,
        filterField: 'checkin_station_id'
      },

      {
        columnDef: 'date_from_in',
        header: 'Από: Παραλαβή',
        filterField: 'date_from_in',
        hasFilter: true,
        filterTemplate: this.dateFromInFilter
      },
      {
        columnDef: 'date_to_in',
        header: 'Εώς: Παραλαβή',
        filterField: 'date_to_in',
        hasFilter: true,
        filterTemplate: this.dateToInFilter
      },
      //----------------------------------------------------------------
      {
        columnDef: 'duration',
        header: 'Ημέρες',
        cell: (element: IQuotesCollection) => `${element.duration}`,
        sortBy: 'duration',
        hasFilter: true
      },
      {
        columnDef: 'customer',
        header: 'Πελάτης',
        cell: (element: IQuotesCollection) => `${element.customer_text ?? ''}`,
        sortBy: 'customer_text',
        hasFilter: true,
        filterTemplate: this.payerFilter,
        filterField: 'payer'
      },
      {
        columnDef: 'phone',
        header: 'Τηλέφωνο',
        cell: (element: IQuotesCollection) => `${element.phone?? ''}`,
        sortBy: 'phone',
        hasFilter: true,
        filterTemplate: this.phoneFilter
      },
      {
        columnDef: 'booking_source_agent',
        header: 'Πηγή - Συνεργάτης',
        cell: (element: IQuotesCollection) => `${element.booking_source?.profiles?.el?.title?? '' + ' - ' + element.agent?.name?? ''}`,
      },
      {
        columnDef: 'booking_source',
        header: 'Πηγή',
        filterField: 'source_id',
        hasFilter: true,
        filterTemplate: this.sourceFilter
      },
      {
        columnDef: 'agent',
        header: 'Συνεργάτης',
        filterField: 'agent_id',
        hasFilter: true,
        filterTemplate: this.agentFilter
      },
      {
        columnDef: 'status',
        header: 'Κατάσταση',
        cell: (element: IQuotesCollection) => `${element.status}`,
        sortBy: 'status',
        hasFilter: true,
        filterTemplate: this.statusFilter,
      },
      {
        columnDef: 'cancel_reason_id',
        header: 'Αιτιολογία Ακύρωσης',
        hasFilter: true,
        filterField: 'cancel_reason_id',
        filterTemplate: this.cancelReasonFilter
      },
      {//date filters and station out-----------------
        columnDef: 'station_checkout',
        header: 'Σταθμός Παράδοσης',
        cell: (element: IQuotesCollection) => `${element.station_checkout?.title}`,
        sortBy: 'checkout_station.current_profile.title',
        hasFilter: true,
        filterTemplate: this.stationOutFilter,
        filterField: 'checkout_station_id'
      },
      {
        columnDef: 'checkout_datetime',
        header: 'Ημ/νία Παράδοσης',
        cell: (element: IQuotesCollection) => `${this.changeDateFormat(element.checkout_datetime.substring(0, 16))}`,
        sortBy: 'checkout_datetime'
      },
      {
        columnDef: 'checkout_place',
        header: 'Τόπος Παράδοσης',
        cell: (element: IQuotesCollection) => `${element.checkout_place?.name}`,
        sortBy: 'checkout_place_text',
      },
      //===================
      {
        columnDef: 'date_from_out',
        header: 'Από: Παράδοση',
        filterField: 'date_from_out',
        hasFilter: true,
        filterTemplate: this.dateFromOutFilter
      },
      {
        columnDef: 'date_to_out',
        header: 'Εώς: Παράδοση',
        filterField: 'date_to_out',
        hasFilter: true,
        filterTemplate: this.dateToOutFilter
      },
      {//date filters for created at-----------------
        columnDef: 'date_from_at',
        header: 'Από: Ημ/νία Εισαγωγής',
        filterField: 'date_from_at',
        hasFilter: true,
        filterTemplate: this.dateFromAtFilter
      },
      {
        columnDef: 'date_to_at',
        header: 'Εώς: Ημ/νία Εισαγωγής',
        filterField: 'date_to_at',
        hasFilter: true,
        filterTemplate: this.dateToAtFilter
      },

    ];
  }


ngAfterContentChecked(): void {
  //Called after every check of the component's or directive's content.
  //Add 'implements AfterContentChecked' to the class.
  //pre select filter
  if (this.totalQuotesSrv.preSelectStatus.getValue()) {
    // console.log([this.totalQuotesSrv.preSelectStatus.getValue()]);
     this.matRef2.options.first.select();
    //this.matRef2.ngControl.control.patchValue([this.totalQuotesSrv.preSelectStatus.getValue()]);
    // console.log(this.matRef2.options);
    this.totalQuotesSrv.preSelectStatus.next(null);
  }
}


  changeDateFormat(date: string): string {//Convert yyyy-MM-dd to MM/dd/yyyy
    let time = '';
    if (date.length > 10) {
      time = date.split(' ')[1];
      date = date.split(' ')[0];
    }
    let splitDate = date.split('-');
    var year = splitDate[0];
    var month = splitDate[1];
    var day = splitDate[2];

    return day + '\/' + month + '\/' + year + ' ' + time;
  }

  clear() {
    this.matRef.options.forEach((data: MatOption) => data.deselect());
  }

  clear2() {
    this.matRef2.options.forEach((data: MatOption) => data.deselect());
  }

  clearCancel() {
    this.matRef3.value = null;
  }

  first(v: string) {
    console.log(v);
  }

  clearDates1() { this.input1.value = null; }
  clearDates2() { this.input2.value = null; }
  clearDates3() { this.input3.value = null; }
  clearDates4() { this.input4.value = null; }
  clearDates5() { this.input5.value = null; }
  clearDates6() { this.input6.value = null; }

  //---------source auto agent include----------------//
  source_id: string = '';
  includeSourceAgent = [];
  @ViewChild('source', { static: false }) source_id_Ref: AbstractSelectorComponent<any>;
  sourceParams: GetParams = { filters: [] };

  sourceEvent() {
    this.selectorSrv.searchSource.subscribe(res => {
      this.source_id = res?.id;
      // this.form.controls.source_id.patchValue(res?.id)
      this.includeSourceAgent = [];//clear

      // this.form.controls.program_id.patchValue(res?.program_id);
      this.agentSrv.get().subscribe(res => {
        this.sourceParams.filters['include_id[]'] = [];//first ini specific filter val
        res.data.forEach((item) => {
          if (item.booking_source_id == this.source_id) {
            this.includeSourceAgent.push(item.id);
            //prepare agent filter
            this.sourceParams.filters['include_id[]'].push(item.id)
          };
          //nullify agent
          setTimeout(() => {
            // this.form.controls.agent_id.patchValue(null);
          }, 200);
          if (this.agent_id_Ref) {
            this.agent_id_Ref.selector.data = [];
            this.agent_id_Ref.selector.selectControl.patchValue(null);
          }
        });
        //fill the agent with options
        console.log(this.sourceParams.filters);
        this.agentSrv.get(this.sourceParams.filters, undefined, -1).subscribe(res => { this.agent_id_Ref.selector.data = res.data; this.sourceParams.filters = { '': null } });
        console.log(this.includeSourceAgent);
      });
    });
  }

  //-------agent_id and sub_account--------//
  agent_id: string = '';
  sub_account_id: string;
  sub_account_type: string;
  @ViewChild('agent', { static: false }) agent_id_Ref: AbstractSelectorComponent<any>;
  includeAgentSub = [];

  agentEvent() {
    this.selectorSrv.searchAgent.subscribe(res => {
      this.agent_id = res?.id;
      //this.form.controls.program_id.patchValue(res?.program_id);
      //console.log(this.agent_id_Ref.selector.selectControl.value + ' agent');
      //console.log(this.agentBool+ ' agent');
      this.includeAgentSub = [];//clear
      this.includeAgentSub = res?.sub_contacts;//filter sub_account
      if (!this.includeAgentSub?.length) {
        this.includeAgentSub = [0];//if empty then zero= no sub accounts
      }
      console.log(this.includeAgentSub);
    });
  }



  @HostListener('document:click', ['$event'])
  EventClick(event: Event) {
    if (this.source_id_Ref.selector.selectControl.value?.id != this.source_id) {//Πηγή
      console.log('some click src');
      // console.log(this.driver_id);
      //  console.log(this.form.controls.driver_id.value);
      this.sourceEvent();
    }
    if (this.agent_id_Ref.selector.selectControl.value?.id != this.agent_id) { //Συνεργάρτης-Πράκτορας
      console.log('some click a');
      // console.log(this.driver_id);
      //  console.log(this.form.controls.driver_id.value);
      this.agentEvent();
    }
  }


  @HostListener('document:keydown', ['$event'])
  EventKeydown(event: Event) {
    if (this.source_id_Ref.selector.selectControl.value?.id != this.source_id) {//Πηγή
      console.log('some key src');
      // console.log(this.driver_id);
      //  console.log(this.form.controls.driver_id.value);
      this.sourceEvent();
    }
    if (this.agent_id_Ref.selector.selectControl.value?.id != this.agent_id) { //Συνεργάρτης-Πράκτορας
      console.log('some key a');
      // console.log(this.driver_id);
      //  console.log(this.form.controls.driver_id.value);
      this.agentEvent();
    }
  }


}
