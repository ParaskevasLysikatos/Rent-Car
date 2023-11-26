
import { SelectionModel } from '@angular/cdk/collections';
import { CdkDragDrop, moveItemInArray } from '@angular/cdk/drag-drop';
import { Component, ElementRef, HostListener, Input, OnInit, ViewChild } from '@angular/core';
import { MatPaginator } from '@angular/material/paginator';
import { Sort } from '@angular/material/sort';
import { MatTable, MatTableDataSource } from '@angular/material/table';
import { Router, ActivatedRoute } from '@angular/router';
import { fadeInRight400ms } from 'src/@vex/animations/fade-in-right.animation';
import { fadeInUp400ms } from 'src/@vex/animations/fade-in-up.animation';
import { stagger40ms } from 'src/@vex/animations/stagger.animation';
import { ConfirmationDialogService } from '../confirmation-dialog/confirmation-dialog.service';
import { IPreview } from '../_interfaces/preview.interface';
import { ApiService } from '../_services/api-service.service';
import { AuthService } from '../_services/auth.service';
import { SpinnerService } from '../_services/spinner.service';
import { IColumn } from './column.interface';
import icColumn from '@iconify/icons-ic/outline-view-column';
import { IFilter } from '../_interfaces/filter.interface';
import { FormControl } from '@angular/forms';
import { TooltipPosition } from '@angular/material/tooltip';
import { PaymentTotalService } from '../payment/payment-total.service';
import { InvoiceTotalService } from '../invoices/invoice-total.service';
import { QuoteTotalService } from '../quotes/quote-total.service';
import { RentalTotalService } from '../rental/rental-total.service';
import { TotalBookingService } from '../booking/total-booking.service';
import { MatInput } from '@angular/material/input';
import * as XLSX from 'xlsx';
import { ContactService } from '../contact/contact.service';
import { IContact } from '../contact/contact.interface';
import { IDriver } from '../driver/driver.interface';
import { DriverService } from '../driver/driver.service';
import { ICompany } from '../company/company.interface';
import { CompanyService } from '../company/company.service';
import { IAgentCollection } from '../agent/agent-collection.interface';
import { AgentService } from '../agent/agent.service';
import { VehicleExchangesService } from '../vehicle-exchanges/vehicle-exchanges.service';
import { IVehicleExchangesCollection } from '../vehicle-exchanges/vehicle-exchanges-collection.interface';
import { ITransition } from '../transition/transition.interface';
import { TransitionService } from '../transition/transition.service';
import { VehicleService } from '../vehicle/vehicle.service';
import { IVehicleCollection } from '../vehicle/vehicle-collection.interface';
import { ITypes } from '../types/types.interface';
import { TypesService } from '../types/types.service';
import { IOptionsCollection } from '../options/options-collection.interface';
import { OptionsService } from '../options/options.service';
import { ICharacteristicsCollection } from '../characteristics/characteristics-collection.interface';
import { CharacteristicsService } from '../characteristics/characteristics.service';
import { ICategoriesCollection } from '../categories/categories-collection.interface';
import { CategoriesService } from '../categories/categories.service';
import { IVisitCollection } from '../visit/visit-collection.interface';
import { VisitService } from '../visit/visit.service';
import { LocationService } from '../locations/location.service';
import { IStation } from '../stations/station.interface';
import { StationService } from '../stations/station.service';
import { PlaceService } from '../places/place.service';
import { ILanguagesCollection } from '../languages/languages-collection.interface';
import { LanguagesService } from '../languages/languages.service';
import { IBookingSource } from '../booking-source/booking-source.interface';
import { BookingSourceService } from '../booking-source/booking-source.service';
import { IRateCodeCollection } from '../rate-code/rate-code-collection.interface';
import { RateCodeService } from '../rate-code/rate-code.service';
import { IUserCollection } from '../user/user-collection.interface';
import { UserService } from '../user/user.service';
import { IRolesCollection } from '../roles/roles-collection.interface';
import { RolesService } from '../roles/roles.service';
import { IDocumentTypeCollection } from '../document-type/document-type-collection.interface';
import { DocumentTypeService } from '../document-type/document-type.service';
import { IDocumentsCollection } from '../documents/documents-collection.interface';
import { DocumentsService } from '../documents/documents.service';
import { IVehicleStatus } from '../vehicle-status/vehicle-status.interface';
import { VehicleStatusService } from '../vehicle-status/vehicle-status.service';
import { IBrand } from '../brand/brand.interface';
import { BrandService } from '../brand/brand.service';
import { IColorType } from '../color-type/color-type.interface';
import { ColorTypeService } from '../color-type/color-type.service';
import moment from 'moment';
import icSearch from '@iconify/icons-ic/twotone-search';
import { MyLoadingService } from '../my-loading/my-loading.service';
import { PreviewComponent } from '../preview/preview.component';
export interface PeriodicElement {
  name: string;
  position: number;
  weight: number;
  symbol: string;
}

@Component({
  selector: 'app-table',
  templateUrl: './table.component.html',
  styleUrls: ['./table.component.scss'],
  animations: [
    stagger40ms,
    fadeInUp400ms,
    fadeInRight400ms
  ],
})
export class TableComponent implements OnInit {
  @Input() columns: IColumn[] = [];
  @Input() displayedColumns!: Array<string>;
  // allColumns!: any[];
  @Input() dataSource!: Array<any>;
  @ViewChild(MatTable, { static: true }) table!: MatTable<any>;
  @ViewChild(MatPaginator, { static: true }) paginator!: MatPaginator;
  selection = new SelectionModel<PeriodicElement>(true, []);
  length!: number;
  matTableDataSource: MatTableDataSource<any> = new MatTableDataSource();
  pageSize: number;
  sortBy: string;
  sortDirection: string;
  filters: IFilter[] = [];
  icColumn = icColumn;
  hasFilters: boolean;

  preSelectFilters: boolean = true;

  customUrl!: string;
  customUrl2!: string;
  positionOptions: TooltipPosition[] = ['right', 'left', 'above', 'below'];
  position = new FormControl(this.positionOptions[0]);
  position2 = new FormControl(this.positionOptions[1]);
  position3 = new FormControl(this.positionOptions[2]);
  position4 = new FormControl(this.positionOptions[3]);
  icSearch = icSearch;

  test: any;


  excelColumns: IColumn[] = [];
  @ViewChild(MatTable, { static: true }) tableExcel!: MatTable<any>;

  constructor(private dialogSrv: ConfirmationDialogService, protected spinnerSrv: SpinnerService,
    protected elementRef: ElementRef, protected authSrv: AuthService, public apiSrv: ApiService<any>,
    protected router: Router, protected route: ActivatedRoute, private urlSrv: Router, public loadSrv: MyLoadingService,
    protected paymentTotalSrv: PaymentTotalService, protected totalInvSrv: InvoiceTotalService
    , private totalQuotesSrv: QuoteTotalService, private totalRenSrv: RentalTotalService,
    private totalBookingSrv: TotalBookingService, private contactSrv: ContactService<IContact>,
    private driverSrv: DriverService<IDriver>, private companiesSrv: CompanyService<ICompany>,
    private agentSrv: AgentService<IAgentCollection>, private V_ExchangeSrv: VehicleExchangesService<IVehicleExchangesCollection>
    , private transitionSrv: TransitionService<ITransition>, private vehicleSrv: VehicleService<IVehicleCollection>
    , private typesSrv: TypesService<ITypes>, private optionsSrv: OptionsService<IOptionsCollection>
    , private charSrv: CharacteristicsService<ICharacteristicsCollection>, private visitSrv: VisitService<IVisitCollection>,
    private categoriesSrv: CategoriesService<ICategoriesCollection>, private locationSrv: LocationService,
    private stationSrv: StationService<IStation>, private placeSrv: PlaceService, private languageSrv: LanguagesService<ILanguagesCollection>,
    private sourceSrv: BookingSourceService<IBookingSource>, private rateCodeSrv: RateCodeService<IRateCodeCollection>
    , private userSrv: UserService<IUserCollection>, private roleSrv: RolesService<IRolesCollection>,
    private docTypeSrv: DocumentTypeService<IDocumentTypeCollection>, private docSrv: DocumentsService<IDocumentsCollection>
    , private v_statusSrv: VehicleStatusService<IVehicleStatus>, public brandSrv: BrandService<IBrand>, public colorSrv: ColorTypeService<IColorType>) { }

  ngOnInit(): void {
    this.customUrl = this.urlSrv.url.split('/')[1];
    // this.customUrl2 = this.urlSrv.url.split('/')[2] ?? '';
    // console.log(this.customUrl2);

    if (!this.paginator.pageSize) {
      this.paginator.pageSize = this.authSrv.getPerPage();
    }
    this.pageSize = this.paginator.pageSize;
    this.displayedColumns.unshift('index');
    this.displayedColumns.unshift('select');

    this.columns.forEach(column => {
      column.visible = this.displayedColumns.includes(column.columnDef);
      if (column.hasFilter) {
        const filter: IFilter = {
          label: column.header,
          field: column.columnDef,
          valueFunction: (value: any) => value
        };
        if (column.filterField) {
          filter.field = column.filterField;
        }
        if (column.filterTemplate) {
          filter.templateRef = column.filterTemplate;
        }
        if (column.filterTemplateGeneral) {
          filter.templateRef = column.filterTemplateGeneral;
        }
        if (column.hasOwnProperty('filterValue')) {
          filter.valueFunction = column.filterValue;
        }

        // if (this.urlSrv.url.includes('rentals') && filter.field == 'status' && this.totalRenSrv.bookmarkRental.getValue() == null) {//rentals pre-selected filters
        //   console.log(this.apiSrv.url);
        //   filter.value = ['active'];//array for multiple select
        //   this.preSelectFilters = true;
        //    this.filterSearch();
        //   // console.log(this.filters);
        // }
        // if (this.urlSrv.url.includes('bookings') && filter.field == 'status' && this.totalRenSrv.bookmarkRental.getValue() == null) {//bookings pre-selected filters
        //   console.log(this.apiSrv.url);
        //   filter.value = ['pending'];//array for multiple select
        //   this.preSelectFilters = true;
        //   this.filterSearch();
        //   // console.log(this.filters);
        // }
        // else if (this.urlSrv.url.includes('quotes') && filter.field == 'status') {//quotes pre-selected filters
        //   console.log(this.apiSrv.url);
        //   filter.value = ['active'];//array for multiple select
        //   this.preSelectFilters = true;
        //   this.filterSearch();
        //   // console.log(this.filters);
        // }

        filter.value = this.route.snapshot.queryParams[filter.field] ?? this.route.snapshot.queryParams[filter.field + '[]'];
        this.filters.push(filter);
      }
    });
    //excel
    this.excelColumns = this.columns;

    this.hasFilters = this.filters.filter(filter => filter.value).length > 0;
    this.paginator.page.subscribe((page) => {
      const params: any = {
        relativeTo: this.route,
        queryParams: {
          page: this.paginator.pageIndex + 1
        }
      }
      if (this.pageSize != this.paginator.pageSize) {
        params.queryParams.perPage = page.pageSize;
        this.pageSize = page.pageSize;
      }
      if (this.sortBy && this.sortDirection) {
        params.queryParams.sortBy = this.sortBy;
        params.queryParams.sortDirection = this.sortDirection;
      }
      //if (!this.urlSrv.url.includes('rentals')){
      this.router.navigate([], params);
      // }
    });
    // if (!this.urlSrv.url.includes('rentals') && !this.urlSrv.url.includes('bookings') && !this.urlSrv.url.includes('quotes') ){
    if (this.loadSrv.enterFirstTimePreview.getValue()) {
      this.loadDataSource();
      this.loadSrv.enterFirstTimePreview.next(true);
    }
    // }
  }

  drop(event: CdkDragDrop<string[]>) {
    moveItemInArray(this.columns, event.previousIndex, event.currentIndex);
  }


  loadDataSource(): void {
    // this.spinnerSrv.showSpinner(this.elementRef);
    this.apiSrv.get({}, this.paginator.pageIndex + 1, this.paginator.pageSize).subscribe(
      res => {
        this.dataLoaded(res);
        this.spinnerSrv.hideSpinner();
      }, err => {
        console.log(err);
      }
    );
  }

  dataLoaded(res: IPreview<any>) {
    this.paginator.pageIndex = res.meta.current_page - 1;
    this.paginator.pageSize = res.meta.per_page;
    let index = this.paginator.pageIndex * this.paginator.pageSize + 1;
    res.data.forEach(item => {
      item.index = index++;
    });
    this.matTableDataSource.data = res.data;
    this.dataSource = res.data;
    this.test = this.matTableDataSource.data;
    this.length = res.meta.total;

    this.customUrl2 = this.urlSrv.url.split('/')[1] ?? '';//not on init, first runs this
    //console.log(this.customUrl2);
    if (this.customUrl2.match('payment')) {// to get with filters the total payment
      //console.log('mpike');
      if (res.data) {
        this.paymentTotalSrv.total_paymentSub.next(res.data[0]);
      }
      else {
        this.paymentTotalSrv.total_paymentSub.next(null);
      }
    }
    if (this.customUrl2.match('invoices')) {// to get with filters the total invoices
      //console.log('mpike');
      if (res.data) {
        this.totalInvSrv.total_invoiceSub.next(res.data[0]);
      }
      else {
        this.totalInvSrv.total_invoiceSub.next(null);
      }
    }
    if (this.customUrl2.match('quotes')) {// to get with filters the total quotes
      //console.log('mpike');
      if (res.data) {
        this.totalQuotesSrv.total_quotesSub.next(res.data[0]);
      }
      else {
        this.totalQuotesSrv.total_quotesSub.next(null);
      }
    }
    if (this.customUrl2.match('rentals')) {// to get with filters the total rental
      //console.log('mpike');
      if (res.data) {
        this.totalRenSrv.total_rentalSub.next(res.data[0]);
      }
      else {
        this.totalRenSrv.total_rentalSub.next(null);
      }
    }
    if (this.customUrl2.match('bookings')) {// to get with filters the total booking
      //console.log('mpike');
      if (res.data) {
        this.totalBookingSrv.total_bookingSub.next(res.data[0]);
      }
      else {
        this.totalBookingSrv.total_bookingSub.next(null);
      }
    }
    if (this.customUrl2.match('contacts')) {// to get with filters the total contact
      //console.log('mpike');
      if (res.data) {
        this.contactSrv.total_ContactSub.next(res.data[0]);
      }
      else {
        this.contactSrv.total_ContactSub.next(null);
      }
    }
    if (this.customUrl2.match('drivers')) {// to get with filters the total driver
      //console.log('mpike');
      if (res.data) {
        this.driverSrv.total_DriverSub.next(res.data[0]);
      }
      else {
        this.driverSrv.total_DriverSub.next(null);
      }
    }
    if (this.customUrl2.match('companies')) {// to get with filters the total companies
      //console.log('mpike');
      if (res.data) {
        this.companiesSrv.total_CompaniesSub.next(res.data[0]);
      }
      else {
        this.companiesSrv.total_CompaniesSub.next(null);
      }
    }
    if (this.customUrl2.match('agents')) {// to get with filters the total agents
      //console.log('mpike');
      if (res.data) {
        this.agentSrv.total_AgentSub.next(res.data[0]);
      }
      else {
        this.agentSrv.total_AgentSub.next(null);
      }
    }
    if (this.customUrl2.match('vehicle-exchanges')) {// to get with filters the total v_exchanges
      //console.log('mpike');
      if (res.data) {
        this.V_ExchangeSrv.total_V_ExchangeSub.next(res.data[0]);
      }
      else {
        this.V_ExchangeSrv.total_V_ExchangeSub.next(null);
      }
    }
    if (this.customUrl2.match('transition')) {// to get with filters the total transition
      //console.log('mpike');
      if (res.data) {
        this.transitionSrv.total_TransitionSub.next(res.data[0]);
      }
      else {
        this.transitionSrv.total_TransitionSub.next(null);
      }
    }
    if (this.customUrl2.match('vehicles')) {// to get with filters the total vehicles
      //console.log('mpike');
      if (res.data) {
        this.vehicleSrv.total_VehicleSub.next(res.data[0]);
      }
      else {
        this.vehicleSrv.total_VehicleSub.next(null);
      }
    }
    if (this.customUrl2.match('types')) {// to get with filters the total types=group
      //console.log('mpike');
      if (res.data) {
        this.typesSrv.total_TypesSub.next(res.data[0]);
      }
      else {
        this.typesSrv.total_TypesSub.next(null);
      }
    }
    if (this.customUrl2.match('options')) {// to get with filters the total options
      //console.log('mpike');
      if (res.data) {
        this.optionsSrv.total_OptionsSub.next(res.data[0]);
      }
      else {
        this.optionsSrv.total_OptionsSub.next(null);
      }
    }
    if (this.customUrl2.match('characteristics')) {// to get with filters the total charact..
      //console.log('mpike');
      if (res.data) {
        this.charSrv.total_CharSub.next(res.data[0]);
      }
      else {
        this.charSrv.total_CharSub.next(null);
      }
    }
    if (this.customUrl2.match('categories')) {// to get with filters the total categories
      //console.log('mpike');
      if (res.data) {
        this.categoriesSrv.total_CategoriesSub.next(res.data[0]);
      }
      else {
        this.categoriesSrv.total_CategoriesSub.next(null);
      }
    }
    if (this.customUrl2.match('visit')) {// to get with filters the total visit
      //console.log('mpike');
      if (res.data) {
        this.visitSrv.total_VisitSub.next(res.data[0]);
      }
      else {
        this.visitSrv.total_VisitSub.next(null);
      }
    }
    if (this.customUrl2.match('locations')) {// to get with filters the total location
      //console.log('mpike');
      if (res.data) {
        this.locationSrv.total_LocationSub.next(res.data[0]);
      }
      else {
        this.locationSrv.total_LocationSub.next(null);
      }
    }
    if (this.customUrl2.match('stations')) {// to get with filters the total station
      //console.log('mpike');
      if (res.data) {
        this.stationSrv.total_StationSub.next(res.data[0]);
      }
      else {
        this.stationSrv.total_StationSub.next(null);
      }
    }
    if (this.customUrl2.match('places')) {// to get with filters the total places
      //console.log('mpike');
      if (res.data) {
        this.placeSrv.total_PlaceSub.next(res.data[0]);
      }
      else {
        this.placeSrv.total_PlaceSub.next(null);
      }
    }
    if (this.customUrl2.match('languages')) {// to get with filters the total languages
      //console.log('mpike');
      if (res.data) {
        this.languageSrv.total_LanguageSub.next(res.data[0]);
      }
      else {
        this.languageSrv.total_LanguageSub.next(null);
      }
    }
    if (this.customUrl2.match('booking-sources')) {// to get with filters the total booking sources
      //console.log('mpike');
      if (res.data) {
        this.sourceSrv.total_SourceSub.next(res.data[0]);
      }
      else {
        this.sourceSrv.total_SourceSub.next(null);
      }
    }
    if (this.customUrl2.match('rate-codes')) {// to get with filters the total rate code
      //console.log('mpike');
      if (res.data) {
        this.rateCodeSrv.total_RateCodeSub.next(res.data[0]);
      }
      else {
        this.rateCodeSrv.total_RateCodeSub.next(null);
      }
    }
    if (this.customUrl2.match('users')) {// to get with filters the total user
      //console.log('mpike');
      if (res.data) {
        this.userSrv.total_UserSub.next(res.data[0]);
      }
      else {
        this.userSrv.total_UserSub.next(null);
      }
    }
    if (this.customUrl2.match('roles')) {// to get with filters the total roles
      //console.log('mpike');
      if (res.data) {
        this.roleSrv.total_RoleSub.next(res.data[0]);
      }
      else {
        this.roleSrv.total_RoleSub.next(null);
      }
    }
    if (this.customUrl2.match('document-types')) {// to get with filters the total doc types
      //console.log('mpike');
      if (res.data) {
        this.docTypeSrv.total_DocTypeSub.next(res.data[0]);
      }
      else {
        this.docTypeSrv.total_DocTypeSub.next(null);
      }
    }
    if (this.customUrl2.match('documents')) {// to get with filters the total docs
      //console.log('mpike');
      if (res.data) {
        this.docSrv.total_DocSub.next(res.data[0]);
      }
      else {
        this.docSrv.total_DocSub.next(null);
      }
    }
    if (this.customUrl2.match('status')) {// to get with filters the total vehicle status
      //console.log('mpike');
      if (res.data) {
        this.v_statusSrv.total_V_StatusSub.next(res.data[0]);
      }
      else {
        this.v_statusSrv.total_V_StatusSub.next(null);
      }
    }
    if (this.customUrl2.match('brand')) {// to get with filters the total brand
      //console.log('mpike');
      if (res.data) {
        this.brandSrv.total_BrandSub.next(res.data[0]);
      }
      else {
        this.brandSrv.total_BrandSub.next(null);
      }
    }
    if (this.customUrl2.match('color_types')) {// to get with filters the total colors
      //console.log('mpike');
      if (res.data) {
        this.colorSrv.total_ColorSub.next(res.data[0]);
      }
      else {
        this.colorSrv.total_ColorSub.next(null);
      }
    }
  }

  deleteSingle(element: any) {
    this.apiSrv.delete(element.id).subscribe(
      // tslint:disable-next-line: variable-name
      _res => {
        this.loadDataSource();
        // const index = this.dataSource.indexOf(element);
        console.log(element.index);
        //this.dataSource.splice(element.index, 1);
        this.matTableDataSource.data.splice(element.index - 1, 1);
        // this.table.renderRows();
      }
    );
  }

  delete(element: any): void {
    this.dialogSrv.showDialog('Είστε σίγουροι ότι θέλετε να διαγράψετε αυτή την εγγραφή;').subscribe(result => {
      if (result) {
        this.deleteSingle(element);
      }
    });
  }

  /** Whether the number of selected elements matches the total number of rows. */
  isAllSelected() {
    const numSelected = this.selection.selected.length;
    const numRows = this.dataSource.length;
    return numSelected === numRows;
  }

  /** Selects all rows if they are not all selected; otherwise clear selection. */
  masterToggle() {
    // if (this.isAllSelected()) {
    //   this.selection.clear();
    //   return;
    // }
    // this.selection.select(...this.dataSource);
    this.dataSource = this.test;
    console.log(this.dataSource);
    this.isAllSelected() ?
      this.selection.clear() :
      this.dataSource.forEach(row => this.selection.select(row));

  }

  /** The label for the checkbox on the passed row */
  checkboxLabel(row?: PeriodicElement): string {
    if (!row) {
      return `${this.isAllSelected() ? 'deselect' : 'select'} all`;
    }
    return `${this.selection.isSelected(row) ? 'deselect' : 'select'} row ${row.position + 1}`;
  }

  bulk_delete() {
    if (this.selection.selected.length > 0) {
      this.dialogSrv.showDialog('Είστε σίγουροι ότι θέλετε να διαγράψετε τις επιλεγμένες εγγραφές;').subscribe(result => {
        if (result) {
          for (const selected of this.selection.selected) {
            this.deleteSingle(selected);
          }
        }
      });
      this.table.renderRows();
    }
  }

  get enabledColumns() {
    const cols = this.columns.filter(column => column.visible).map(column => column.columnDef);
    cols.unshift('select', 'index');
    cols.push('actions');
    return cols;
  }

  get enabledColumnsExcel() {
    const cols = this.excelColumns.filter(column => column.visible).map(column => column.columnDef);
    cols.unshift('index');
    return cols;
  }

  toggleColumnVisibility(column, event) {
    event.stopPropagation();
    event.stopImmediatePropagation();
    column.visible = !column.visible;
  }

  sortData(evt: Sort) {
    this.sortBy = evt.active;
    this.sortDirection = evt.direction;
    const queryParams: any = {};
    if (this.sortBy && this.sortDirection) {
      queryParams.sortBy = this.sortBy;
      queryParams.sortDirection = this.sortDirection;
    }
    this.router.navigate([], { relativeTo: this.route, queryParams });
  }

  takeInnerId(obj: any): any {
    if (typeof obj === 'object') {
      if (obj instanceof Date) {//for app-datetimepicker
        return moment(obj).format('YYYY-MM-DD HH:mm:ss');
      }
      console.log(typeof obj);
      return obj.id;
    }
    console.log("not obj " + typeof obj);
    return obj;
  }


  filterSearch() {
    const queryParams: any = {};
    this.filters.filter(filter => filter.value).forEach(filter => {
      if (Array.isArray(filter.value)) {
        queryParams[filter.field + '[]'] = [];
        filter.value.forEach(value => {
          queryParams[filter.field + '[]'].push(filter.valueFunction(this.takeInnerId(value)));
        })
      }
      else {
        queryParams[filter.field] = filter.valueFunction(this.takeInnerId(filter.value));
      }
    });
    let currentUser = JSON.parse(localStorage.getItem('loggedUser'));
    // if (this.urlSrv.url.includes('rentals') && this.preSelectFilters) {//rentals pre-selected filters
    //   queryParams['status'] = [];
    //   queryParams['status'].push('active');
    //   queryParams['checkout_station_id'] = [];
    //   queryParams['checkin_station_id'] = [];
    //  // this.authSrv.user.subscribe(res => {
    //   queryParams['checkout_station_id'].push(currentUser.station_id);
    //   queryParams['checkin_station_id'].push(currentUser.station_id);
    //   //  })
    //   this.preSelectFilters = false;
    //   this.totalRenSrv.preSelectStatus.next('active');
    //   console.log(this.filters);
    // }
    // else if (this.urlSrv.url.includes('bookings') && this.preSelectFilters) {//bookings pre-selected filters
    //   queryParams['status'] = [];
    //   queryParams['status'].push('pending');
    //   //extra pre-select filter station
    //   queryParams['checkout_station_id'] = [];
    //   queryParams['checkin_station_id'] = [];
    //  // this.authSrv.user.subscribe(res => {
    //   queryParams['checkout_station_id'].push(currentUser.station_id);
    //   queryParams['checkin_station_id'].push(currentUser.station_id);
    //  // });
    //   this.preSelectFilters = false;
    //   this.totalBookingSrv.preSelectStatus.next('pending');
    //   console.log(this.filters);
    // }
    // else if (this.urlSrv.url.includes('quotes') && this.preSelectFilters) {//quotes pre-selected filters
    //   queryParams['status'] = [];
    //   queryParams['status'].push('active');
    //   queryParams['checkout_station_id'] = [];
    //   queryParams['checkin_station_id'] = [];
    //  // this.authSrv.user.subscribe(res => {
    //   queryParams['checkout_station_id'].push(currentUser.station_id);
    //   queryParams['checkin_station_id'].push(currentUser.station_id);
    // //  });
    //   this.preSelectFilters = false;
    //   this.totalQuotesSrv.preSelectStatus.next('active');
    //   console.log(this.filters);
    // }
    this.router.navigate([], { relativeTo: this.route, queryParams });
  }

  @ViewChild('search') searchInput: ElementRef;
  filterSearchGeneral(search: string) {
    const queryParams: any = {};
    queryParams['search'] = [];
    queryParams['search'].push(search);
    this.router.navigate([], { relativeTo: this.route, queryParams });
  }

  clearSearch() {
    this.searchInput.nativeElement.value = null;
  }


  resetFilters() {
    this.filters.forEach(filter => filter.value = null);
    this.loadDataSource();
    this.router.navigate([], { relativeTo: this.route });
  }



  @HostListener('document:keypress', ['$event'])
  handleKeyboardEvent(event: KeyboardEvent) {
    if (['Enter'].indexOf(event.key) !== -1) {
      this.filterSearch();
      this.filterSearchGeneral(this.searchInput.nativeElement.value);
    }
  }

  navigateTo(id: string) {
    this.router.navigate([id], { relativeTo: this.route })
  }

  //(keydown.enter) = '$event.target.blur()' on html for enter to blur

  /*name of the excel-file which will be downloaded. */
  fileName = 'report';
  excelDownload() {
    /* table id is passed over here */
    let element = document.getElementById('excel-table');
    const ws: XLSX.WorkSheet = XLSX.utils.table_to_sheet(element);
    /* generate workbook and add the worksheet */
    const wb: XLSX.WorkBook = XLSX.utils.book_new();

    let excelCol = { wch: 26 };
    let wscols = [];
    this.excelColumns.forEach(() => wscols.push(excelCol));

    ws['!cols'] = wscols;

    XLSX.utils.book_append_sheet(wb, ws, 'Sheet1');
    /* save to file */
    let pagename = this.customUrl2;
    let myDate = moment().format('L');
    XLSX.writeFile(wb, this.fileName + '-' + pagename + '_' + myDate + '.xlsx');
  }

  toggleColumnVisibilityExcel(column, event) {
    // event.stopPropagation();
    event.preventDefault();
    event.stopImmediatePropagation();
    column.visible = !column.visible;
    this.excelColumns = this.columns;
    this.excelColumns = this.excelColumns.filter((item) => item.visible);
    this.tableExcel.renderRows();
  }

}
