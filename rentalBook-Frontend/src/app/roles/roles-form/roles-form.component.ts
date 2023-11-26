import { Component, Injector, OnInit } from '@angular/core';
import { Validators } from '@angular/forms';
import { AbstractFormComponent } from 'src/app/abstract-form/abstract-form.component';


@Component({
  selector: 'app-roles-form',
  templateUrl: './roles-form.component.html',
  styleUrls: ['./roles-form.component.scss']
})
export class RolesFormComponent extends AbstractFormComponent implements OnInit {
  form = this.fb.group({
    id: [, Validators.required],
    title:[, Validators.required],
    description:[]
  });

  constructor(protected injector: Injector) {
    super(injector);
  }
}
