import { Component, Injector, OnInit } from '@angular/core';
import { Validators } from '@angular/forms';
import { AbstractFormComponent } from 'src/app/abstract-form/abstract-form.component';


@Component({
  selector: 'app-licence-plate-form',
  templateUrl: './licence-plate-form.component.html',
  styleUrls: ['./licence-plate-form.component.scss']
})
export class LicencePlateFormComponent extends AbstractFormComponent implements OnInit {
  form = this.fb.group({
    id: [],
    licence_plate: [, Validators.required],
    registration_date: [, Validators.required],
    documents: [],
  });

  constructor(protected injector: Injector) {
    super(injector);
  }
}
