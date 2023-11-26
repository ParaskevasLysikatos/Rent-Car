import { ComponentType } from '@angular/cdk/portal';
import { Component, ElementRef, EventEmitter, Input, OnInit, TemplateRef, ViewChild } from '@angular/core';
import { FormControl } from '@angular/forms';
import { MatAutocompleteSelectedEvent } from '@angular/material/autocomplete';
import { _MatOptionBase } from '@angular/material/core';
import { TooltipPosition } from '@angular/material/tooltip';
import { Subject } from 'rxjs';
import { debounceTime, filter, takeUntil, tap } from 'rxjs/operators';
import { FormDialogService } from 'src/app/form-dialog/form-dialog.service';
import { ApiService } from 'src/app/_services/api-service.service';
import { AutocompleteSelectorParams } from './autocomplete-selector-params.interface';

export interface AutoCompleteObject {
  id: string;
  name: string;
}

@Component({
  selector: 'app-autocomplete-selector',
  templateUrl: './autocomplete-selector.component.html',
  styleUrls: ['./autocomplete-selector.component.scss']
})
export class AutocompleteSelectorComponent implements OnInit {
  @Input() optionTemplate!: TemplateRef<any>;
  @Input() params!: AutocompleteSelectorParams<any>;
  @Input() editBtn: boolean= true;
  @Input() addBtn: boolean = false;
  @Input() phone: string = '';
  include: any[]=[];

  oldSearchValue: string|null = null;
  searchCtrl = new FormControl();
  idCtrl = new FormControl();
  searching = false;
  onChange = new EventEmitter();

  options: any[] = [];
  //optionsMatch: any[] = [];
  positionOptions: TooltipPosition[] = ['right', 'left', 'above', 'below'];
  position = new FormControl(this.positionOptions[0]);
  show: boolean;

  createComponent!: ComponentType<any>;
  editComponent!: ComponentType<any>;

  protected _onDestroy = new Subject<void>();

  @Input()
  get value(): AutoCompleteObject {
    return {
      id: this.idCtrl.value,
      name: this.searchCtrl.value
    };
  }
  set value(object: AutoCompleteObject | null) {
    this.idCtrl.patchValue(object?.id, {emitEvent: false});
    this.searchCtrl.patchValue(object?.name, {emitEvent: false});
  }

  constructor(public elementRef: ElementRef, public formDialogSrv: FormDialogService, protected apiSrv: ApiService<any>) {
    this.searchCtrl.valueChanges.pipe(
      filter(term => !!term),
      tap(() => this.searching = true),
      takeUntil(this._onDestroy),
      debounceTime(300),
      takeUntil(this._onDestroy)
    ).subscribe(term => {
      if (term !== this.oldSearchValue) {
        let filters: any = {};
        filters.term = term;
        filters['include_id[]'] = [];
        for (let selector of this.include) {
          filters['include_id[]'].push(selector);
        }

        console.log(this.include);
        console.log(filters);
        filters = this.getFilters(filters);

        this.idCtrl.patchValue(null);
        this.onChange.emit();
        this.apiSrv.get({...filters},undefined, -1).subscribe(res => {
          //this.options = res.data;
           this.options = [];// clear
           res.data.filter(obj => { if (this.apiSrv.url.includes('drivers')) { obj?.full_name.includes(this.searchCtrl.value) ? this.options.push(obj) : '' }
           else if (this.apiSrv.url.includes('places')){
             obj?.profiles?.el?.title.includes(this.searchCtrl.value) ? this.options.push(obj) : ''
           } });
          // if(this.optionsMatch.length<1){
          //   this.optionsMatch = this.options;
          // }
          this.searching = false;
        });
      }
    });
  }

  ngOnInit(): void {
    this.editComponent = this.params.editComponent;
    this.createComponent = this.params.createComponent;
    this.include=this.params.include ?? this.include;
  }

  ngAfterContentChecked(): void {
    this.include = this.params.include ?? this.include;
  }

  ngOnDestroy(): void {
    this._onDestroy.next();
    this._onDestroy.complete();
  }

  viewValue = (option: _MatOptionBase) => {
    this.include = this.params.include ?? this.include;
    return option.viewValue;
  }

  getFilters = (filters: any): any => {
    return filters;
  }

  selectionChange(change: MatAutocompleteSelectedEvent): void {
    const option = change.option;
    if (option) {
      this.idCtrl.patchValue(option.value.id);
    } else {
      this.idCtrl.patchValue(null);
    }
    this.oldSearchValue = this.viewValue(option);
    this.searchCtrl.patchValue(this.viewValue(option));
    this.onChange.emit();
  }

  edit = (id: string) => {
    this.formDialogSrv.showDialog(this.editComponent, {id});
    this.formDialogSrv.dialogRef.afterClosed().subscribe((res) => { if(this.apiSrv.url.includes('drivers')){
      this.searchCtrl.patchValue(res.full_name);
      setTimeout(() =>this.idCtrl.patchValue(res.id),350);
    }
   });
  }

  add = (passValue: string) => {
    this.formDialogSrv.showDialog(this.createComponent, { phoneDialog: this.phone, fullNameDialog:passValue });
    this.formDialogSrv.dialogRef.afterClosed().subscribe((res) => { this.idCtrl.patchValue(res.id) });
  }
}
