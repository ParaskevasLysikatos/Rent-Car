import { DatePipe } from '@angular/common';
import { AfterViewInit, Component, ElementRef, Host, Injector, OnInit, Optional, ViewChild } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { BehaviorSubject } from 'rxjs/internal/BehaviorSubject';
import { IColumn } from '../table/column.interface';
import { TableComponent } from '../table/table.component';
import { ApiService } from '../_services/api-service.service';
import { SpinnerService } from '../_services/spinner.service';

@Component({
  template: ''
})
export abstract class PreviewComponent<Type> implements OnInit, AfterViewInit {
  @ViewChild(TableComponent, {static: true}) tableComponent!: TableComponent;
  data: Array<Type> = [];
  columns!: IColumn[];
  abstract displayedColumns: any;
  protected elementRef: ElementRef;
  protected spinnerSrv: SpinnerService;
  protected apiSrv: ApiService<Type>;
  protected route: ActivatedRoute;
  protected router: Router;

  typePrev!: string | null;


  constructor(protected injector: Injector) {
    this.elementRef = injector.get(ElementRef);
    this.spinnerSrv = injector.get(SpinnerService);
    this.apiSrv = injector.get(ApiService);
    this.route = injector.get(ActivatedRoute);
    this.router = injector.get(Router);
  }

  ngOnInit(): void {
    this.typePrev = this.router.url.split('/')[2];
    console.log(this.typePrev);
    if (this.typePrev) {
      this.apiSrv.url = this.apiSrv.url+'/'+this.typePrev;
    }
    // if (this.router.url.includes('rentals')) {//deactivate for preselect filters from table comp
    // }
    // else if (this.router.url.includes('bookings')){
    // }
    // else if (this.router.url.includes('quotes')){
    // }
    // else{
      this.route.data.subscribe(() => {
        this.tableComponent.filters.forEach(filter => {
          filter.value = this.route.snapshot.queryParams[filter.field] ?? this.route.snapshot.queryParams[filter.field + '[]'];
        });
        this.tableComponent.hasFilters = this.tableComponent.filters.filter(filter => filter.value).length > 0;
        this.tableComponent.sortBy = this.route.snapshot.queryParams.sortBy;
        this.tableComponent.sortDirection = this.route.snapshot.queryParams.sortDirection;
        this.tableComponent.dataLoaded(this.route.snapshot.data.tableData);
      });
  //}
  }

  ngAfterViewInit(): void {
  }




  ngOnDestroy(){}
}
