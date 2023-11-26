import { Directive, ElementRef, HostListener } from '@angular/core';

@Directive({
  selector: 'input[fuelRange]'
})
export class FuelRangeDirective {

  // Only want positive integers
  private regex: RegExp = new RegExp(/^\d+$/g);
  // Allow key codes for special events Backspace, tab, end, home
  private specialKeys = ['Backspace', 'Tab', 'End', 'Home'];

  constructor(private _el: ElementRef) { }

  @HostListener('input', ['$event']) onInputChange(event) {
    const initalValue = this._el.nativeElement.value[0];//single digit allow only

    this._el.nativeElement.value = initalValue.replace(/[^1-8]*/g, ''); //range 1-8
    if (initalValue !== this._el.nativeElement.value) {
      event.stopPropagation();
    }
  }

}
