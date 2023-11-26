import { Component, Injector, OnInit } from '@angular/core';
import { Validators } from '@angular/forms';
import { AbstractFormComponent } from 'src/app/abstract-form/abstract-form.component';


@Component({
  selector: 'app-color-type-form',
  templateUrl: './color-type-form.component.html',
  styleUrls: ['./color-type-form.component.scss']
})
export class ColorTypeFormComponent extends AbstractFormComponent implements OnInit {
  form = this.fb.group({
    id: null,
  title: [],
  international_title: [],
  hex_code: []
  });

  constructor(protected injector: Injector) {
    super(injector);
  }
}
