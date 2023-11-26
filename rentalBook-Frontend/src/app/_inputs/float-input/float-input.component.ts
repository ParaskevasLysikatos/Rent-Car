import { FocusMonitor } from '@angular/cdk/a11y';
import { coerceBooleanProperty } from '@angular/cdk/coercion';
import { Component, ElementRef, forwardRef, HostBinding, Injector, Input, OnInit, Optional, Renderer2, Self, ViewChild } from '@angular/core';
import { ControlValueAccessor, FormControl, NgControl, NG_VALUE_ACCESSOR } from '@angular/forms';
import { MatFormFieldControl } from '@angular/material/form-field';
import { Subject } from 'rxjs';

@Component({
  selector: 'app-float-input',
  templateUrl: './float-input.component.html',
  styleUrls: ['./float-input.component.scss'],
  providers: [
    {
      provide: MatFormFieldControl,
      useExisting: FloatInputComponent
    }
  ]
})
export class FloatInputComponent implements OnInit, ControlValueAccessor, MatFormFieldControl<any> {
  static nextId = 0;
  inputControl: FormControl = new FormControl();
  onChange: any = () => {};
  stateChanges = new Subject<void>();

  @HostBinding() id = `app-float-input-${FloatInputComponent.nextId++}`;

  @Input()
  get value(): number {
    return this.inputControl.value;
  }
  set value(number: number) {
    this.inputControl.patchValue(number);
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
    return !this.value && this.value !== 0;
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
      this.inputControl.disable();
    } else {
      this.inputControl.enable();
    }
    this.stateChanges.next();
  }
  protected _disabled = false;

  get errorState(): boolean {
    return this.inputControl.invalid && this.touched;
  }

  controlType = 'app-float-input';

  @Input('aria-describedby') userAriaDescribedBy!: string;

  setDescribedByIds(ids: string[]) {
    const controlElement = this.elementRef.nativeElement
      .querySelector('.app-float-input-container')!;
    controlElement.setAttribute('aria-describedby', ids.join(' '));
  }

  onContainerClick(event: MouseEvent) {
    if ((event.target as Element).tagName.toLowerCase() != 'input') {
      this.elementRef.nativeElement.querySelector('input').focus();
    }
  }

  elementRef: ElementRef;
  focusMonitor: FocusMonitor;
  params: any = {};

  constructor(protected injector: Injector, @Optional() @Self() public ngControl: NgControl) {
    this.elementRef = this.injector.get(ElementRef);
    this.focusMonitor = this.injector.get(FocusMonitor);
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
  }

  inputChanged(number: string) {
    this.value = Number(number);
    this.onChange(this.value);
  }

  writeValue(number: number): void {
    this.value = number;
  }

  registerOnChange(fn: any): void {
    this.onChange = fn;
  }

  registerOnTouched(): void {

  }
}
