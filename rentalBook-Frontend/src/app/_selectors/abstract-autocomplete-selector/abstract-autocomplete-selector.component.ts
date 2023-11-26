import { FocusMonitor } from '@angular/cdk/a11y';
import { coerceBooleanProperty } from '@angular/cdk/coercion';
import { ComponentType } from '@angular/cdk/portal';
import { Component, ElementRef, EventEmitter, HostBinding, Injector, Input, Optional, Output, Self, ViewChild } from '@angular/core';
import { ControlValueAccessor, NgControl } from '@angular/forms';
import { MatFormFieldControl } from '@angular/material/form-field';
import { Subject } from 'rxjs';
import { ApiService } from 'src/app/_services/api-service.service';
import { AutoCompleteObject, AutocompleteSelectorComponent } from '../autocomplete-selector/autocomplete-selector.component';

@Component({
  selector: 'app-abstract-autocomplete-selector',
  templateUrl: './abstract-autocomplete-selector.component.html',
  styleUrls: ['./abstract-autocomplete-selector.component.scss']
})
export abstract class AbstractAutocompleteSelectorComponent<Type> implements MatFormFieldControl<any>, ControlValueAccessor {
  static nextId = 0;
  @ViewChild(AutocompleteSelectorComponent) selector!: AutocompleteSelectorComponent;
  abstract readonly EditComponent: ComponentType<any>|null;
  abstract readonly CreateComponent: ComponentType<any>|null;
  @Input() include: any[]=[];
  @Output() dataLoaded: EventEmitter<any | void> = new EventEmitter();

  @HostBinding() id = `app-autocomplete-selector-${AbstractAutocompleteSelectorComponent.nextId++}`;

  options: Type[] = [];

  stateChanges = new Subject<void>();

  @Input()
  get value(): AutoCompleteObject {
    return this.selector?.value;
  }
  set value(object: AutoCompleteObject | null) {
    if (this.selector) {
      this.selector.value = object;
      this.stateChanges.next();
    }
  }

  @Input()
  get placeholder() {
    return this._placeholder;
  }
  set placeholder(plh) {
    this._placeholder = plh;
    this.stateChanges.next();
  }
  protected _placeholder!: string;

  _focused = false;
  touched = false;

  get focused(): boolean {
    return this._focused;
  }
  set focused(value: boolean) {
    this._focused = value;
    this.stateChanges.next();
  }

  get empty() {
    let search = this.selector?.searchCtrl.value;
    return !search;
  }

  @HostBinding('class.floating')
  get shouldLabelFloat() {
    return this.focused || !this.empty;
  }

  @Input()
  get required() {
    return this._required;
  }
  set required(req) {
    this._required = coerceBooleanProperty(req);
    this.stateChanges.next();
  }
  protected _required = false;

  @Input()
  get disabled(): boolean { return this._disabled; }
  set disabled(value: boolean) {
    this._disabled = coerceBooleanProperty(value);
    if (this._disabled) {
      this.selector.idCtrl.disable();
      this.selector.searchCtrl.disable();
    } else {
      this.selector.idCtrl.enable();
      this.selector.searchCtrl.enable();
    }
    this.stateChanges.next();
  }
  protected _disabled = false;

  get errorState(): boolean {
    return this.selector?.searchCtrl.invalid && this.touched;
  }

  controlType = 'app-autocomplete-selector';

  @Input('aria-describedby') userAriaDescribedBy!: string;

  setDescribedByIds(ids: string[]) {
    const controlElement = this.elementRef.nativeElement
      .querySelector('.app-autocomplete-selector-container')!;
    controlElement.setAttribute('aria-describedby', ids.join(' '));
  }

  onContainerClick(event: MouseEvent) {
    if ((event.target as Element).tagName.toLowerCase() != 'input') {
      this.elementRef.nativeElement.querySelector('input').focus();
    }
  }

  elementRef: ElementRef;
  focusMonitor: FocusMonitor;
  apiSrv: ApiService<Type>;
  params: any = {};

  onChange: any = () => {};

  constructor(protected injector: Injector, @Optional() @Self() public ngControl: NgControl) {
    this.elementRef = this.injector.get(ElementRef);
    this.focusMonitor = this.injector.get(FocusMonitor);
    this.apiSrv = this.injector.get(ApiService);
    this.focusMonitor.monitor(this.elementRef.nativeElement, true)
      .subscribe(focusOrigin => {
        this.focused = !!focusOrigin;
      })
    if (this.ngControl != null) {
      // Setting the value accessor directly (instead of using
      // the providers) to avoid running into a circular import.
      this.ngControl.valueAccessor = this;
    }
  }

  ngOnInit(): void {
    this.params = {
      editComponent: this.EditComponent,
      createComponent: this.CreateComponent,
      include: this.include
    };
  }

  ngAfterViewInit(): void {
    this.selector.onChange.subscribe(() => {
        this.stateChanges.next();
        this.onChange(this.value);
      }
    );

    this.dataLoaded.emit(this.selector.value);
  }

   ngAfterContentChecked(): void {
    this.include = this.params.include ?? this.include;
   // this.dataLoaded.emit(this.selector?.value);// to calc payers on clear
 }


  ngOnDestroy(): void {
    this.focusMonitor.stopMonitoring(this.elementRef.nativeElement);
  }

  writeValue(object: AutoCompleteObject|null): void {
    this.value = object;
  }

  registerOnChange(fn: any): void {
    this.include = this.params.include ?? this.include;
    this.dataLoaded.emit(this.selector?.value);// to calc payers on clear
    this.onChange = fn;
  }

  registerOnTouched(): void {

  }
}
