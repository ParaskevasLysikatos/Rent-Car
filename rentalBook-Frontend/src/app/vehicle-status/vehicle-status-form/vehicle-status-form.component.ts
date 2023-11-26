import { Component, Injector, OnInit } from '@angular/core';
import { Validators } from '@angular/forms';
import { MultilingualFormComponent } from 'src/app/multilingual-form/multilingual-form.component';

@Component({
  selector: 'app-vehicle-status-form',
  templateUrl: './vehicle-status-form.component.html',
  styleUrls: ['./vehicle-status-form.component.scss']
})
export class VehicleStatusFormComponent extends MultilingualFormComponent implements OnInit {
  form = this.fb.group({
    id: null
  });

  constructor(protected injector: Injector) {
    super(injector);
  }
}
