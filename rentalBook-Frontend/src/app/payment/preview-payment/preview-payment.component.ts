import { DatePipe } from '@angular/common';
import { AfterViewChecked, Component, HostListener, Injector, TemplateRef, ViewChild } from '@angular/core';
import { MatOption } from '@angular/material/core';
import { MatInput } from '@angular/material/input';
import { MatSelect } from '@angular/material/select';
import { Router } from '@angular/router';
import { retry, delay, take } from 'rxjs';
import { PreviewComponent } from 'src/app/preview/preview.component';
import { IStation } from 'src/app/stations/station.interface';
import { StationService } from 'src/app/stations/station.service';
import { AbstractSelectorComponent } from 'src/app/_selectors/abstract-selector/abstract-selector.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { IPaymentCollection } from '../payment-collection.interface';
import { PaymentMethodService } from '../payment-method.service';
import { PaymentTotalService } from '../payment-total.service';
import { PaymentService } from '../payment.service';

@Component({
  selector: 'app-preview-payment',
  templateUrl: './preview-payment.component.html',
  styleUrls: ['./preview-payment.component.scss'],
  providers: [{ provide: ApiService, useClass: PaymentService }]
})
export class PreviewPaymentComponent extends PreviewComponent<IPaymentCollection> {
  displayedColumns = ['sequence_number', 'payer', 'user_id', 'amount', 'balance',
                       'payment_method', 'payment_datetime','rental','booking', 'actions'];
  type!: string | null;
  @ViewChild('user_filter', { static: true }) userFilter: TemplateRef<any>;
  @ViewChild('station_filter', { static: true }) stationFilter: TemplateRef<any>;

  @ViewChild('payer_filter', { static: true }) payerFilter: TemplateRef<any>;

  @ViewChild('method_filter', { static: true }) methodFilter: TemplateRef<any>;

  @ViewChild('date_from_filter', { static: true }) dateFromFilter: TemplateRef<any>;
  @ViewChild('date_to_filter', { static: true }) dateToFilter: TemplateRef<any>;

  @ViewChild('rental_filter', { static: true }) rentalFilter: TemplateRef<any>;
  @ViewChild('booking_filter', { static: true }) bookingFilter: TemplateRef<any>;

  @ViewChild('matRef') matRef: MatSelect;
  //clear dates
  @ViewChild('input1', { read: MatInput }) input1: MatInput;
  @ViewChild('input2', { read: MatInput }) input2: MatInput;

  url_payment!: boolean;
  url_refund!: boolean;
  url_pre_auth!: boolean;
  url_refund_pre_auth!: boolean;
  methods!: any;

  @ViewChild('station', { static: false }) station_id_Ref: AbstractSelectorComponent<any>;


  protected apiSrv!: PaymentService<IPaymentCollection>;
  constructor(protected injector: Injector, protected router: Router, protected paymentMethodSrv: PaymentMethodService,
    public totalPaymentSrv: PaymentTotalService, public stationSrv: StationService<IStation>, public urlSrv: Router) {
    super(injector);
  }
  ngOnInit() {
    super.ngOnInit();

    this.paymentMethodSrv.get().subscribe(res => {
      this.methods = res;
    });

    this.stationSrv.get({}, undefined, -1).subscribe(res => {
      this.station_id_Ref.selector.data = res.data;
    });

    this.columns = [
      {
        columnDef: 'sequence_number',
        header: 'SEQ#',
        cell: (element: IPaymentCollection) => `${element.sequence_number}`,
        hasFilter: true
      },
      {
        columnDef: 'payer',
        header: 'Πελάτης',
        cell: (element: IPaymentCollection) => `${element.payer.name}`,
        hasFilter: true,
        filterTemplate: this.payerFilter,
        filterField: 'payer_id',
      },
      {
        columnDef: 'user_id',
        header: 'Χρήστης',
        cell: (element: IPaymentCollection) => `${element.user_id}`,
        hasFilter: true,
        filterField: 'user_id',
        filterTemplate: this.userFilter
      },
      {
        columnDef: 'amount',
        header: 'Σύνολο',
        cell: (element: IPaymentCollection) => `${element.amount}`
      },
      {
        columnDef: 'balance',
        header: 'Υποσύνολο',
        cell: (element: IPaymentCollection) => `${element.balance}`
      },
      {
        columnDef: 'payment_method',
        header: 'Τρόπος Πληρωμής',
        cell: (element: IPaymentCollection) => `${element.payment_method}`,
        hasFilter: true,
        filterTemplate: this.methodFilter,
        filterField: 'method'
      },
      {
        columnDef: 'payment_datetime',
        header: 'Ημερομηνία Πληρωμής',
        cell: (element: IPaymentCollection) => `${String(element.payment_datetime).substring(0,10)}`
      },
      {
        columnDef: 'station',
        header: 'Σταθμός:',
        hasFilter: true,
        filterField: 'station_id',
        filterTemplate: this.stationFilter
      },
      {
        columnDef: 'date_from',
        header: 'Ημερομηνία Από',
        hasFilter: true,
        filterField: 'date_from',
        filterTemplate: this.dateFromFilter,
      },
      {
        columnDef: 'date_to',
        header: 'Ημερομηνία Εώς',
        hasFilter: true,
        filterField: 'date_to',
        filterTemplate: this.dateToFilter,
      },
      {
        columnDef: 'rental',
        header: 'Μίσθωση',
        cell: (element: IPaymentCollection) => `${element.conRental?.sequence_number ?? ''}`,
        hasFilter: true,
        filterField: 'rental_id',
        filterTemplate: this.rentalFilter,
      },
      {
        columnDef: 'booking',
        header: 'Κράτηση',
        cell: (element: IPaymentCollection) => `${element.booking?.sequence_number ?? ''}`,
        hasFilter: true,
        filterField: 'booking_id',
        filterTemplate: this.bookingFilter,
      },

    ];
    this.onSelectClick();
  }

  ngAfterContentChecked(): void {
    //Called after every check of the component's or directive's content.
    //Add 'implements AfterContentChecked' to the class.
    // this.type = this.urlSrv.url.split('/')[2];
    // if (this.type.includes('?')) {
    //   this.type = this.type.split('?')[0];
    //   this.apiSrv.setType(this.type);
    // }
    // console.log(this.type);

  }

  onSelectClick() {
    if (this.router.url == '/payments/payment') {
      this.url_payment = true;//
      this.url_refund = false;
      this.url_pre_auth = false;
      this.url_refund_pre_auth = false;

    }
    else if (this.router.url == '/payments/refund') {
      this.url_payment = false;
      this.url_refund = true;//
      this.url_pre_auth = false;
      this.url_refund_pre_auth = false;
    }
    else if (this.router.url == '/payments/pre-auth') {
      this.url_payment = false;
      this.url_refund = false;
      this.url_pre_auth = true;//
      this.url_refund_pre_auth = false;
    }
    else if (this.router.url == '/payments/refund-pre-auth'){
      this.url_payment = false;
      this.url_refund = false;
      this.url_pre_auth = false;
      this.url_refund_pre_auth = true;//
    }
    // console.log(this.router.url);
  }


  ngOnDestroy() {
    super.ngOnDestroy();
    this.apiSrv.setType(null);
   // this.totalPaymentSrv.total_paymentSub.next(null);
  }

  ngAfterViewChecked() {
    this.onSelectClick();
   // console.log(this.totalPaymentSrv.total_paymentSub.getValue());
  }

  first(v: string) {
    console.log(v);
  }

  clear() {
    this.matRef.options.forEach((data: MatOption) => data.deselect());
  }

  clearDates1() { this.input1.value = null; }
  clearDates2() { this.input2.value = null; }




}
