import { Directive, Input } from '@angular/core';
import { NgControl } from '@angular/forms';

@Directive({
  selector: '([formControlName], [formControl])[disabledControl]'
})
export class DisabledcontrolDirective {
  @Input() set disabledControl(state: boolean) {
    const action = state ? 'disable' : 'enable';
    setTimeout(() => {
      if (this.ngControl.control) {
        this.ngControl.control[action]();
      }
    });
  }

  constructor(private readonly ngControl: NgControl) {}
}
