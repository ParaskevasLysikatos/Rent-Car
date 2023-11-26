import { Component, Injector, TemplateRef, ViewChild } from '@angular/core';
import { MatOption } from '@angular/material/core';
import { MatInput } from '@angular/material/input';
import { MatSelect } from '@angular/material/select';
import { delay, retry } from 'rxjs';
import { PreviewComponent } from 'src/app/preview/preview.component';
import { IStation } from 'src/app/stations/station.interface';
import { StationService } from 'src/app/stations/station.service';
import { AbstractSelectorComponent } from 'src/app/_selectors/abstract-selector/abstract-selector.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { InvoiceTotalService } from '../invoice-total.service';
import { IInvoicesCollection } from '../invoices-collection.interface';
import { InvoicesService } from '../invoices.service';

@Component({
  selector: 'app-preview-invoices',
  templateUrl: './preview-invoices.component.html',
  styleUrls: ['./preview-invoices.component.scss'],
  providers: [{provide: ApiService, useClass: InvoicesService}]
})
export class PreviewInvoicesComponent extends PreviewComponent<IInvoicesCollection> {
  displayedColumns = ['sequence_number','name','rental_sequence_number','type','date','total','payment_way','actions'];

  @ViewChild('sequence_number', { static: true }) sequence_number: TemplateRef<any>;

  @ViewChild('date_from_filter', { static: true }) dateFromFilter: TemplateRef<any>;
  @ViewChild('date_to_filter', { static: true }) dateToFilter: TemplateRef<any>;

  @ViewChild('station_filter', { static: true }) stationFilter: TemplateRef<any>;
  @ViewChild('payer_filter', { static: true }) payerFilter: TemplateRef<any>;

  @ViewChild('type_filter', { static: true }) typeFilter: TemplateRef<any>;
  @ViewChild('aade_filter', { static: true }) aadeFilter: TemplateRef<any>;

  @ViewChild('station', { static: false }) station_id_Ref: AbstractSelectorComponent<any>;

  @ViewChild('matRef') matRef: MatSelect;
  @ViewChild('matRef2') matRef2: MatSelect;
  //clear dates
  @ViewChild('input1', { read: MatInput }) input1: MatInput;
  @ViewChild('input2', { read: MatInput }) input2: MatInput;

  constructor(protected injector: Injector, public totalInvSrv: InvoiceTotalService, public stationSrv: StationService<IStation>) {
    super(injector);
  }

  ngOnInit() {
    super.ngOnInit();
    setTimeout(() => this.matRef.options.first.select(), 2000);//slow on initialize needs timeout
    setTimeout(() => this.matRef2.options.first.select(), 2000);//slow on initialize needs timeout
    this.stationSrv.get({}, undefined, -1).subscribe(res => {
      this.station_id_Ref.selector.data = res.data;
    });

    this.columns = [
      {
        columnDef: 'sequence_number',
        header: 'Σειρά',
       // cell: (element: IInvoicesCollection) => `${element.sequence_number}`,
        cellTemplate:this.sequence_number,
        hasFilter: true
      },
      {
        columnDef: 'name',
        header: 'Πελάτης',
        cell: (element: IInvoicesCollection) => `${element.instance?.name}`,
        hasFilter: true,
        filterTemplate: this.payerFilter,
        filterField: 'invoicee_id_find',
      },
      {
        columnDef: 'rental_sequence_number',
        header: 'Μισθωτήριο',
        cell: (element: IInvoicesCollection) => `${element.instance?.rental_sequence_number}`
      },
      {
        columnDef: 'type',
        header: 'Τύπος',
        cell: (element: IInvoicesCollection) => `${element.type}`,
        hasFilter: true,
        filterTemplate: this.typeFilter,
      },
      {
        columnDef: 'date',
        header: 'Ημερομηνία',
        cell: (element: IInvoicesCollection) => `${element.date}`
      },
      {
        columnDef: 'total',
        header: 'Ποσό',
        cell: (element: IInvoicesCollection) => `${element.total}`
      },
      {
        columnDef: 'payment_way',
        header: 'Τρόποι Πλήρωμης',
        cell: (element: IInvoicesCollection) => `${element.payment_way }`
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
        columnDef: 'aade',
        header: 'ΑΑΔΕ',
        hasFilter: true,
        filterTemplate: this.aadeFilter,
        filterField: 'sent_to_aade'
      },
    ];
  }


  first(v: string) {
    console.log(v);
  }

  clear() {
    this.matRef.options.forEach((data: MatOption) => data.deselect());
  }
  clear2() {
    this.matRef2.options.forEach((data: MatOption) => data.deselect());
  }

  clearDates1() { this.input1.value = null; }
  clearDates2() { this.input2.value = null; }

}
