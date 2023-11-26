import { Component, EventEmitter, forwardRef, OnInit, Output } from '@angular/core';
import { ControlValueAccessor, FormControl, NG_VALUE_ACCESSOR } from '@angular/forms';
import { ITime } from './time.interface';

@Component({
  selector: 'app-timepicker',
  templateUrl: './timepicker.component.html',
  styleUrls: ['./timepicker.component.scss'],
  providers: [
    {
      provide: NG_VALUE_ACCESSOR,
      useExisting: forwardRef(() => TimepickerComponent),
      multi: true
    },
  ]
})
export class TimepickerComponent implements OnInit, ControlValueAccessor {
  timepickerControl = new FormControl();
  @Output() changeTime: EventEmitter<ITime> = new EventEmitter();

  constructor() { }

  onChange: any = (value: any) => {};

  ngOnInit(): void {
  }

  set value (time: string) {
    time = time.replace(/\D/g, '');
    if (time.length > 4) {
        time = time.substring(0, 4);
    }
    let hoursStr = String(time);
    let hours = Number(time);
    while (hours > 24 && hoursStr.length > 0) {
      hoursStr = hoursStr.slice(0, -1);
      hours = Number(hoursStr);
    }

    if (hoursStr.length <= 0 || hours == 24) {
      hoursStr = '0';
    }

    if (hoursStr.length > 2) {
      hoursStr = hoursStr.slice(0, 2);
    }

    let minutesStr = time.slice(hoursStr.length);
    let minutes = Number(minutesStr);

    while (minutes >= 60 && minutesStr.length > 0) {
      minutesStr = minutesStr.slice(0, -1);
    }
    minutes = Number(minutesStr);

    hoursStr = hoursStr.padStart(2, '0');
    if (minutes > 6) {
      minutesStr = minutesStr.padStart(2, '0');
    } else {
      minutesStr = minutesStr.padEnd(2, '0');
    }

    const value = hoursStr + ':' + minutesStr;
    this.timepickerControl.patchValue(value);
    this.onChange(value);
  }

  writeValue(value: any): void {
    if (value instanceof Date) {
      value = value.getHours() + ':' + value.getMinutes();
    } else if (value) {
      this.value = value;
    }
    this.timepickerControl.patchValue(value);
  }

  registerOnChange(fn: any): void {
    this.onChange = fn;
  }

  registerOnTouched(): void {

  }

  changeValue() {
    this.setTime(this.timepickerControl.value);
  }

  setTime(time: string) {
    this.value = time;
    const value = this.timepickerControl.value.split(':');
    const hoursStr = value[0];
    const minutesStr = value[1];
    this.changeTime.emit({hours: Number(hoursStr), minutes: Number(minutesStr)});
  }
}
