import { formatDate } from '@angular/common';
import { AfterViewInit, Component, EventEmitter, forwardRef, Input, OnInit, Output, ViewChild } from '@angular/core';
import { ControlValueAccessor, FormControl, NG_VALIDATORS, NG_VALUE_ACCESSOR } from '@angular/forms';
import { MatDatepickerInput } from '@angular/material/datepicker';
import { TimepickerComponent } from '../timepicker/timepicker.component';

@Component({
  selector: 'app-datetimepicker',
  templateUrl: './datetimepicker.component.html',
  styleUrls: ['./datetimepicker.component.scss'],
  providers: [
    {
      provide: NG_VALUE_ACCESSOR,
      useExisting: forwardRef(() => DatetimepickerComponent),
      multi: true
    },
    {
      provide: NG_VALIDATORS,
      useExisting: forwardRef(() => DatetimepickerComponent),
      multi: true,
    },
    // {provide: MatFormFieldControl, useExisting: DatetimepickerComponent}
  ]
})
export class DatetimepickerComponent implements OnInit, ControlValueAccessor {
  minDate!: Date;
  maxDate!: Date;
  valid!:boolean;
  @Input() set min(min: string) {
    if (min) {
      this.minDate = new Date(min);
    }
  }
  @Input() set max(max: string) {
    if (max) {
      this.maxDate = new Date(max);
    }
  }

  @Output() changeDate: EventEmitter<string|null> = new EventEmitter();
  @ViewChild(MatDatepickerInput) datepickerInput !: MatDatepickerInput<any>;
  @ViewChild(TimepickerComponent, {static: true}) timepickerInput !: TimepickerComponent;
  datepickerControl = new FormControl();
  timepickerControl = new FormControl();
  _value!: Date|undefined;

  constructor() {
  }

  onChange: any = (value: any) => {};

  get value(): string|null {
    return this._value ? formatDate(this._value, 'YYYY-MM-dd HH:mm:ss', 'en-US') : null;
  }

  setValue(value: string|Date|null, emitEvent: boolean) {
    if (value) {
      if (value instanceof Date) {
        this._value = value;
        this.datepickerControl.patchValue(value, {emitEvent});
        this.timepickerControl.patchValue(value, {emitEvent});
        this.onChange(this.value);
      } else {
        this._value = new Date(value);
        this.datepickerControl.patchValue(value, {emitEvent});
        this.timepickerControl.patchValue(new Date(value), {emitEvent});
      }
    }
  }

  set value(value: string|null) {
    this.setValue(value, true);
  }

  ngOnInit(): void {
    this.datepickerControl.valueChanges.subscribe(date => {
      if (date) {
        this._value = new Date(date);
        if (this.timepickerControl.value instanceof Date) {
          const time = this.timepickerControl.value;
          this._value.setHours(time.getHours());
          this._value.setMinutes(time.getMinutes());
          this.timepickerControl.patchValue(this._value, {emitEvent: true});
        }
      } else {
        this._value = undefined;
      }
      this.onChange(this.value);
    });
    this.timepickerInput.changeTime.subscribe(time => {
      this._value?.setHours(time.hours);
      this._value?.setMinutes(time.minutes);
      this.onChange(this._value);
      this.changeDate.emit(this.value);
    });
  }

  emitChangeDate(date) {
    this.changeDate.emit(date);
  }

  writeValue(value: any): void {
    this.setValue(value, false);
  }

  registerOnChange(fn: any): void {
    this.onChange = fn;
  }

  registerOnTouched(): void {

  }

  validate(control: FormControl) {
    const isNotValid = this.datepickerControl.invalid || this.timepickerControl.invalid;
    this.valid=isNotValid;
    return isNotValid && {
      invalid: true
    }
  }


}
