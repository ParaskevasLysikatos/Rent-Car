import { AfterViewInit, Component, ElementRef, EventEmitter, Injector, Input, OnInit, Output, QueryList, ViewChildren } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { AbstractSelectorComponent } from '../_selectors/abstract-selector/abstract-selector.component';
import { SpinnerService } from '../_services/spinner.service';

@Component({
  selector: 'app-abstract-form',
  templateUrl: './abstract-form.component.html',
  styleUrls: ['./abstract-form.component.scss']
})
export abstract class AbstractFormComponent implements OnInit, AfterViewInit {
  @Input() canDelete!: boolean;
  @Output() ngSubmit: EventEmitter<void> = new EventEmitter();
  @ViewChildren(AbstractSelectorComponent) selectors!: QueryList<AbstractSelectorComponent<any>>;
  selectorsLoaded = 0;
  abstract form: FormGroup;
  spinnerSrv: SpinnerService;
  fb: FormBuilder;
  elementRef: ElementRef;

  constructor(protected injector: Injector) {
    this.spinnerSrv = injector.get(SpinnerService);
    this.fb = injector.get(FormBuilder);
    this.elementRef = injector.get(ElementRef);
  }

  ngOnInit(): void {

  }

  ngAfterViewInit(): void {
    if (this.selectors.length > 0) {
      this.spinnerSrv.setUpSelectors();
    }
    this.selectors.forEach(selector => {
      selector.dataLoaded.subscribe(() => {
        this.selectorsLoaded++;
        if (this.selectorsLoaded == this.selectors.length) {
          this.spinnerSrv.hideSpinner();
        }
      });
    });
  }

  public onSubmit() {
    this.ngSubmit.emit();
  }
}
