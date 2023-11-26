import { I } from '@angular/cdk/keycodes';
import { ComponentType } from '@angular/cdk/portal';
import { Component, EventEmitter, HostListener, Injector, Input, OnInit, Output, TemplateRef, ViewChild } from '@angular/core';
import { ControlValueAccessor } from '@angular/forms';
import { ICustomer } from 'src/app/customer/customer.interface';
import { ApiService } from 'src/app/_services/api-service.service';
import { SelectorComponent } from '../selector/selector.component';

@Component({
  selector: 'app-abstract-selector',
  templateUrl: './abstract-selector.component.html',
  styleUrls: ['./abstract-selector.component.scss']
})
export abstract class AbstractSelectorComponent<T> implements OnInit, ControlValueAccessor {
  @Input() multiple = false;
  @Input() addBtn = false;
  @Input() editBtn = false;
  @Input() clearBtn = false;
  @Input() label = '';
  @Input() excludeSelectors: Array<AbstractSelectorComponent<any>> = [];
  @Input() include: any[] = [];
  @Input() type_id: any[] = []; //vehicle-selector
  @Input() status2: any[] = []; //vehicle-selector
  @Input() vehicle_status: any[] = []; //vehicle-selector
  @Input() role: any[] = []; //vehicle-selector
  @Input() from: any[] = []; //vehicle-selector
  @Input() to: any[] = []; //vehicle-selector
  @Input() rental_id: any[] = []; //vehicle-selector
  flagStart: boolean = true;
  @Input() initialVal: any[] = [];

  @Output() dataLoaded: EventEmitter<any | void> = new EventEmitter();
  @ViewChild(SelectorComponent, { static: true }) selector !: SelectorComponent;
  data: Array<T> = [];
  params: any = {};
  key!: string;
  abstract readonly EditComponent: ComponentType<any> | null;
  abstract readonly CreateComponent: ComponentType<any> | null;
  readonly fields: any = 'id';
  protected apiSrv: ApiService<T>;

  initBool = true;// for temp selectors init

  constructor(protected injector: Injector) {
    this.apiSrv = injector.get(ApiService);
  }

  onChange: any = () => { };

  ngOnInit(): void {
    this.params = {
      editComponent: this.EditComponent,
      createComponent: this.CreateComponent,
      multiple: this.multiple,
      data: this.data,
      addBtn: this.addBtn,
      editBtn: this.editBtn,
      clearBtn: this.clearBtn,
      label: this.label,
      excludeSelectors: this.excludeSelectors,
      include: this.include,
      type_id: this.type_id,
      status2: this.status2,
      vehicle_status: this.vehicle_status,
      role: this.role,
      from: this.from,
      to: this.to,
      rental_id: this.rental_id,
    }



      if (this.key) {
        this.params.key = this.key;
      }

      this.selector.showEditDialog = (option) => {
        const value = this.getValue(option);
        if (!this.EditComponent) {
          throw new Error("EditComponent is either null either undefined");
        }
        return this.selector.formDialogSrv.showDialog(this.EditComponent, { id: value });
      }

      this.selector.selectControl.valueChanges.subscribe(change => {
        let selected;
        if (Array.isArray(change)) {
          selected = [];
          for (const single of change) {
            const value = this.getValue(single);
            selected.push(value);
          }
        } else {
          const value = this.getValue(change);
          selected = value;
        }
        this.onChange(selected);
        //this.dataLoaded.emit(this.selector.selectControl.value);
      });

    }



  ngAfterViewChecked() {// synchronize selector for filters
    if (this.selector.searching) {
      this.params = {
        editComponent: this.EditComponent,
        createComponent: this.CreateComponent,
        multiple: this.multiple,
        data: this.data,
        addBtn: this.addBtn,
        editBtn: this.editBtn,
        clearBtn: this.clearBtn,
        label: this.label,
        excludeSelectors: this.excludeSelectors,
        include: this.include,
        type_id: this.type_id,
        status2: this.status2,
        vehicle_status: this.vehicle_status,
        role: this.role,
        from: this.from,
        to: this.to,
        rental_id: this.rental_id,
      }
      // this.dataLoaded.emit(this.selector.selectControl.value);// to calc payers on clear
    }
  }



    getValue(object: any): any {
      if (object) {
        let value: any;
        if (Array.isArray(this.fields)) {
          value = {};
          for (const key of this.fields) {
            value[key] = object[key];
          }
        } else {
          value = object[this.fields];
        }
        return value;
      }
      return null;
    }

    setValue(val: any, triggerChange = true) {
      if (val !== undefined && this.selector !== undefined && this.selector.selectControl.value !== val) {
        if (this.multiple) { // when multiple is true( like stations - payments selectors)
          val = Array.isArray(val) ? val : [val];
          const filters: any = {};
          let fields = this.fields;
          if (!Array.isArray(fields)) {
            fields = [fields];
          }
          for (const field of fields) {
            filters[field + '[]'] = val;
          }
          if (val.length > 0) {
              this.apiSrv.get(filters).subscribe(res => { // the callback when selector has multiple true
                const selected = [];
                for (const single of res.data) {
                  selected.push(single);
                }
                this.selector.selectControl.patchValue(selected, { emitEvent: false });
                if (!triggerChange) {
                  this.dataLoaded.emit(this.selector.selectControl.value);
                 // this.selector.change(res.data);
                }
              });
          } else {
            // this.dataLoaded.emit();
          }
        } else if (typeof val === 'object') { // the callback when selector has an object(like customer,subaccount) and needs the whole resource
          const filters = this.getValue(val);
         // console.log(Object.keys(val).length);
          if (val!=undefined && Object.keys(val).length>3){ // avoid first time call requests
              var data = val;
              this.selector.selectControl.patchValue(data, { emitEvent: false });
              if (!triggerChange) {
                this.dataLoaded.emit(this.selector.selectControl.value);
                //this.selector.change(data);
              }
          } else {// normal for  customer,subaccount
            this.apiSrv.get(filters).subscribe(res => {
              var data = res.data[0];
              this.selector.selectControl.patchValue(data, { emitEvent: false });
              if (!triggerChange) {
                this.dataLoaded.emit(this.selector.selectControl.value);
                //this.selector.change(data);
              }
            });
          }

        } else {
            this.apiSrv.edit(val).subscribe(res => { // the callback when selector has the id of the data and needs the whole resource
              this.selector.selectControl.patchValue(res, { emitEvent: false });

              if (!triggerChange) {
                this.dataLoaded.emit(this.selector.selectControl.value);
                //this.selector.change(res);
              }
            });
        }
        if (triggerChange) {
          this.onChange(val);
        }
      } else {
        this.selector.selectControl.patchValue(null, { emitEvent: false });
        // this.dataLoaded.emit();
      }
    }

  set value(val: any) {

      this.setValue(val);
    }

  get value(): any {
      return this.getValue(this.selector?.selectControl?.value);
    }

  get model(): any {
      return this.selector?.selectControl?.value;
    }

    writeValue(value: any): void {
      this.setValue(value, false);
    }

    registerOnChange(fn: any): void {
    this.onChange = fn;
    }

    registerOnTouched(): void {
    }

  }


