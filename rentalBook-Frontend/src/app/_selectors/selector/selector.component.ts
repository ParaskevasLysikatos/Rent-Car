
import { IRental } from 'src/app/rental/rental.interface';
import { Component, HostListener, Input, OnChanges, OnDestroy, OnInit, SimpleChanges, TemplateRef } from '@angular/core';
import { FormControl } from '@angular/forms';
import { MatOptionSelectionChange } from '@angular/material/core';
import { Observable, Subject } from 'rxjs';
import { debounceTime, delay, filter, finalize, last, take, takeUntil, tap } from 'rxjs/operators';
import { FormDialogService } from 'src/app/form-dialog/form-dialog.service';
import { ApiService } from 'src/app/_services/api-service.service';
import { AbstractSelectorComponent } from '../abstract-selector/abstract-selector.component';
import { SelectorParams } from './selector-params.interface';
import { SelectorService } from './selector.service';
import { NotificationService } from 'src/app/_services/notification.service';
import { I } from '@angular/cdk/keycodes';
import { Router } from '@angular/router';

@Component({
  selector: 'app-selector',
  templateUrl: './selector.component.html',
  styleUrls: ['./selector.component.scss']
})
export class SelectorComponent implements OnInit, OnDestroy {
  @Input() params!: SelectorParams<any>;
  @Input() optionTemplate!: TemplateRef<any>;
  excludeSelectors: Array<AbstractSelectorComponent<any>> = [];
  include: any[]=[];
  type_id: any[] = [];
  status2: any[]=[];
  vehicle_status: any[] = [];
  role: any[] = [];
  from: any[] = [];
  to: any[] = [];
  rental_id: any[] = [];
  multiple = false;
  createComponent!: any;
  editComponent!: any;
  data: Array<any> = [];
  label!: string;
  addBtn = false;
  editBtn = false;
  clearBtn = false;
  /** control for filter for server side. */
  public filteringCtl: FormControl = new FormControl();
  selectControl: FormControl = new FormControl();
  key = 'id';

  /** indicate search operation is in progress */
  public searching = false;
  protected _onDestroy = new Subject<void>();

  constructor(public formDialogSrv: FormDialogService, protected apiSrv: ApiService<any>, protected selectorSrv: SelectorService,public notSrv:NotificationService,public router: Router) {
    this.filteringCtl.valueChanges
      .pipe(
        filter(search => !!search),
        tap(() => { this.searching = true;}),
        takeUntil(this._onDestroy),
        debounceTime(350),
       takeUntil(this._onDestroy)
      )
      .subscribe(search => {
        let filters: any = {};
        filters.search = search;
        filters['exclude_id[]'] = [];
        for (let selector of this.excludeSelectors) {
          filters['exclude_id[]'].push(selector.value);
        }
        filters['include_id[]'] = [];

        for (let selector of this.include) {
          filters['include_id[]'].push(selector);
        }


        filters['type_id'] = [];
        for (let selector of this.type_id) {
          filters['type_id'].push(selector);
        }

        filters['status2'] = [];
        for (let selector of this.status2) {
          filters['status2'].push(selector);
        }

        filters['vehicle_status'] = [];
        for (let selector of this.vehicle_status) {
          filters['vehicle_status'].push(selector);
        }

        filters['role[]'] = [];
        for (let selector of this.role) {
          filters['role[]'].push(selector);
        }

        filters['from'] = [];
        for (let selector of this.from) {
          filters['from'].push(selector);
        }

        filters['to'] = [];
        for (let selector of this.to) {
          filters['to'].push(selector);
        }

        filters['rental_id'] = [];
        for (let selector of this.rental_id) {
          filters['rental_id'].push(selector);
        }

        //console.log(this.type_id);
        console.log(filters);
        filters = this.getFilters(filters);
          apiSrv.get(filters).subscribe(res => {
            if(res.data.length>0){
              this.data = [];
              this.data = res.data //filter(data => {console.log(data) ; data.name.includes(search)});
            }else{
             // this.data = this.data.forEach() res.data //filter(data => {console.log(data) ; data.name.includes(search)});
              Object.assign(this.data, ...res.data);
            }

            this.searching = false;
          });
      });
  }

  getFilters = (filters: any): any => {
    return filters;
  }

  get selectedOptions() {
    let selectedOptions = this.selectControl.value ?? [];
    selectedOptions = Array.isArray(selectedOptions) ? selectedOptions : [selectedOptions];
    return selectedOptions;
  }

  get options() {
    if (this.data) {
      var options = this.selectedOptions.concat(this.data.filter(item => !this.selectedOptions.
        find(selectedOption => selectedOption[this.key] == item[this.key])
      ));
    }else{
      var options = this.selectedOptions;
    }
    var optionFiltered = options;
      if(this.searching){
        optionFiltered = this.selectedOptions.filter(data => { if (data?.name) { data?.name.includes(this.filteringCtl.value) } else {
          // console.log(data)
            } });
      }
    return optionFiltered;
  }


  selectionChange(change: MatOptionSelectionChange): void {
    const option = change.source;
    if (!option.selected && !this.data.find(item => item[this.key] == option.value[this.key])) {
      this.data.push(option.value);
    }
  }

  ngOnInit(): void {
    this.createComponent = this.params.createComponent;
    this.editComponent = this.params.editComponent;
    this.multiple = this.params.multiple ?? this.multiple;
    this.data = this.params.data ?? this.data;
    this.addBtn = this.params.addBtn ?? this.addBtn;
    this.editBtn = this.params.editBtn ?? this.editBtn;
    this.clearBtn = this.params.clearBtn ?? this.clearBtn;
    this.key = this.params.key ?? this.key;
    this.label = this.params.label ?? this.label;
    this.excludeSelectors = this.params.excludeSelectors ?? this.excludeSelectors;
    this.include= this.params.include ?? this.include;
    this.type_id = this.params.type_id ?? this.type_id;
    this.status2 = this.params.status2 ?? this.status2;
    this.vehicle_status = this.params.vehicle_status ?? this.vehicle_status;
    this.role = this.params.role ?? this.role;
    this.from = this.params.from ?? this.from;
    this.to = this.params.to ?? this.to;
    this.rental_id = this.params.rental_id ?? this.rental_id;
  }


  ngOnDestroy(): void {
    this._onDestroy.next();
    this._onDestroy.complete();
  }

  change(evt: any):void {
    this.onInput();
    console.log(evt);
    for(let item in evt.value)
    {
      if (item =='IamRental'){
        console.log('rental');
        this.selectorSrv.searchRental.next(evt.value);
        this.selectorSrv.searchRental.subscribe(res => console.log(res));
      }

      else if (item == 'IamDriver') {
        console.log('driver');
        this.selectorSrv.searchDriver.next(evt.value);
        this.selectorSrv.searchDriverTemp.next(evt.value);
        this.selectorSrv.searchDriver.subscribe(res => console.log(res));
      }

     else if (item == 'IamStation') {
        console.log('station');
        this.selectorSrv.searchStation.next(evt.value);
        this.selectorSrv.searchStationTemp.next(evt.value);
        this.selectorSrv.searchStation.subscribe(res => console.log(res));
      }

      else if (item == 'IamSource') {
        console.log('source');
        this.selectorSrv.searchSource.next(evt.value);
        this.selectorSrv.searchSourceTemp.next(evt.value);
        this.selectorSrv.searchSource.subscribe(res => console.log(res));
      }

      else if (item == 'IamAgent') {
        console.log('agent');
        this.selectorSrv.searchAgent.next(evt.value);
        this.selectorSrv.searchAgentTemp.next(evt.value);
        this.selectorSrv.searchAgent.subscribe(res => console.log(res));
      }

      else if (item == 'IamSubAccount') {
        console.log('sub_account');
        this.selectorSrv.searchSubAccount.next(evt.value);
        this.selectorSrv.searchSubAccountTemp.next(evt.value);
        this.selectorSrv.searchSubAccount.subscribe(res => console.log(res));
      }

      else if (item == 'IamGroup') {
        console.log('group');
        this.selectorSrv.searchGroup.next(evt.value);
        this.selectorSrv.searchGroupTemp.next(evt.value);
        this.selectorSrv.searchGroup.subscribe(res => console.log(res));
      }

      else if (item == 'IamVehicle') {
        console.log('vehicle');
        this.selectorSrv.searchVehicle.next(evt.value);
        this.selectorSrv.searchVehicleTemp.next(evt.value);
        this.selectorSrv.searchVehicle.subscribe(res => console.log(res));
      }

      else if (item == 'IamCompany') {
        console.log('company');
        this.selectorSrv.searchCompany.next(evt.value);
        this.selectorSrv.searchCompanyTemp.next(evt.value);
        this.selectorSrv.searchCompany.subscribe(res => console.log(res));
      }
    }
  }

  ngAfterViewChecked() {// synchronize selector for filters
        if (this.searching) {
           this.excludeSelectors = this.params.excludeSelectors ?? this.excludeSelectors;
           this.include = this.params.include ?? this.include;
           this.type_id = this.params.type_id ?? this.type_id;
           this.status2 = this.params.status2 ?? this.status2;
           this.vehicle_status = this.params.vehicle_status ?? this.vehicle_status;
           this.role = this.params.role ?? this.role;
          this.from = this.params.from ?? this.from;
            this.to = this.params.to ?? this.to;
           this.rental_id = this.params.rental_id ?? this.rental_id;
           this.onInput();
           setTimeout(() => { this.searching = false }, 300); // needed because selector will not begin search properly, will need click outside
           }
    }


  showEditDialog = (option: any): Observable<any> => {
    return this.formDialogSrv.showDialog(this.editComponent, {id: option?.id});
  }

  edit(evt: Event, option: any, idx?: number): void {
    if (this.editBtn) {
      this.showEditDialog(option).subscribe(res => {
        if (res !== undefined) {
          if (idx !== undefined) {
            const values = this.selectControl.value;
            values[idx] = res;
            this.selectControl.patchValue(values, { emitEvent: true });
          } else {
           // console.log(res);
            this.selectControl.patchValue(res, { emitEvent: true });
          }
        }
      });
      evt.stopPropagation();
    }
  }


  clear(evt: Event, option: any, idx?: number): void {
    if (this.clearBtn) {
      if (idx !== undefined) {
        const values = this.selectControl.value;
        values[idx] = null;
        this.selectControl.patchValue(values, { emitEvent: true });
      } else {
        this.selectControl.patchValue(null, { emitEvent: true });
      }
      evt.stopPropagation();
    }
  }



  add(evt: Event): void {
    this.formDialogSrv.showDialog(this.createComponent).subscribe(res => {
      if (res !== undefined) {
        let selected;
        if (this.multiple) {
          selected = this.selectControl.value;
          selected.push(res);
          this.data.push(res);
        } else {
         // this.data.push(this.selectControl.value); //on empty comp , create has no id, careful
          this.data.push(res);
          selected = res;
        }
        this.selectControl.patchValue(selected, { emitViewToModelChange: false, emitEvent: true });
      }
    });
    this.formDialogSrv.dialogRef.afterClosed().subscribe((res) => {
      console.log(res);
      for (let r in res) {
        if(r=='IamDriver'){
          this.selectorSrv.createNewObjDriver.next(true);
        }
        else if(r=='IamCompany'){
          this.selectorSrv.createNewObjCompany.next(true);
        }
        else if(r=='IamAgent'){
          this.selectorSrv.createNewObjAgent.next(true);
        }
        else if(r=='IamSource'){
          this.selectorSrv.createNewObjSource.next(true);
        }


      }

      this.changeForCreate(res);
      setTimeout(() => { this.selectorSrv.createNewObjDriver.next(false);
        this.selectorSrv.createNewObjCompany.next(false);
        this.selectorSrv.createNewObjAgent.next(false);
        this.selectorSrv.createNewObjSource.next(false);
         }, 500);
       });
    evt.stopPropagation();
  }

  objectComparison = function(option, value): boolean {
    if (option && value) {
      return option[this.key] == value[this.key];
    }
    return false;
  };

  isArray(obj: any): boolean {
    return Array.isArray(obj);
  }

  onInput() {
   // event.target.value = event.target.value.toUpperCase();
    if (this.filteringCtl.value){
      this.filteringCtl.patchValue(this.lngtype(this.filteringCtl.value.toUpperCase()));
    }
  }


  lngtype(text: string): string {
    // var text = text.replace(/\s/g); //read input value, and remove "space" by replace \s
    //Dictionary for Unicode range of the languages
    var langdic = {
      // "arabic": /[\u0600-\u06FF]/,
      // "persian": /[\u0750-\u077F]/,
      // "Hebrew": /[\u0590-\u05FF]/,
      // "Syriac": /[\u0700-\u074F]/,
      // "Bengali": /[\u0980-\u09FF]/,
      // "Ethiopic": /[\u1200-\u137F]/,
      "Greek and Coptic": /[\u0370-\u03FF]/,
      // "Georgian": /[\u10A0-\u10FF]/,
      // "Thai": /[\u0E00-\u0E7F]/,
      "english": /^[a-zA-Z]+$/
      //add other languages her
    }
    let arrayLang = [];
    let uniqueArray = [];
    //const keys = Object.keys(langdic); //read  keys
    //const keys = Object.values(langdic); //read  values
    const keys = Object.entries(langdic); // read  keys and values from the dictionary
    for (let char of text) {
      Object.entries(langdic).forEach(([key, value]) => {  // loop to read all the dictionary items if not true
        if (value.test(char) == true) {   //Check Unicode to see which one is true
          arrayLang.push(key);
          uniqueArray = [...new Set(arrayLang)]
          //return //document.getElementById("lang_her").innerHTML = key; //print language name if unicode true
          if (uniqueArray.length > 1) {
            this.notSrv.showErrorNotification('careful 2 Languages in same input');
          }
          // console.log(uniqueArray);
        }
      });
    }
    return text;
  }

  changeForCreate(evt: any): void {
    console.log(evt);
    for (let item in evt) {
      if (item == 'IamRental') {
        console.log('rental');
        this.selectorSrv.searchRental.next(evt);
       // this.selectorSrv.searchRental.subscribe(res => console.log(res));
      }

      else if (item == 'IamDriver') {
        console.log('driver');
        this.selectorSrv.searchDriver.next(evt);
        this.selectorSrv.searchDriverTemp.next(evt);
        //this.selectorSrv.searchDriver.subscribe(res => console.log(res));
      }

      else if (item == 'IamStation') {
        console.log('station');
        this.selectorSrv.searchStation.next(evt);
        this.selectorSrv.searchStationTemp.next(evt);
      //  this.selectorSrv.searchStation.subscribe(res => console.log(res));
      }

      else if (item == 'IamSource') {
        console.log('source');
        this.selectorSrv.searchSource.next(evt);
        this.selectorSrv.searchSourceTemp.next(evt);
       // this.selectorSrv.searchSource.subscribe(res => console.log(res));
      }

      else if (item == 'IamAgent') {
        console.log('agent');
        this.selectorSrv.searchAgent.next(evt);
        this.selectorSrv.searchAgentTemp.next(evt);
       // this.selectorSrv.searchAgent.subscribe(res => console.log(res));
      }

      else if (item == 'IamSubAccount') {
        console.log('sub_account');
        this.selectorSrv.searchSubAccount.next(evt);
        this.selectorSrv.searchSubAccountTemp.next(evt);
       // this.selectorSrv.searchSubAccount.subscribe(res => console.log(res));
      }

      else if (item == 'IamGroup') {
        console.log('group');
        this.selectorSrv.searchGroup.next(evt);
        this.selectorSrv.searchGroupTemp.next(evt);
       // this.selectorSrv.searchGroup.subscribe(res => console.log(res));
      }

      else if (item == 'IamVehicle') {
        console.log('vehicle');
        this.selectorSrv.searchVehicle.next(evt);
        this.selectorSrv.searchVehicleTemp.next(evt);
      //  this.selectorSrv.searchVehicle.subscribe(res => console.log(res));
      }

      else if (item == 'IamCompany') {
        console.log('company');
        this.selectorSrv.searchCompany.next(evt);
        this.selectorSrv.searchCompanyTemp.next(evt);
       // this.selectorSrv.searchCompany.subscribe(res => console.log(res));
      }
    }
  }


}
