import { Component, Injector, OnInit } from '@angular/core';
import { Validators } from '@angular/forms';
import { MultilingualFormComponent } from 'src/app/multilingual-form/multilingual-form.component';

@Component({
  selector: 'app-station-form',
  templateUrl: './station-form.component.html',
  styleUrls: ['./station-form.component.scss']
})
export class StationFormComponent extends MultilingualFormComponent implements OnInit {
  form = this.fb.group({
    id: [],
    code: [],
    address: [],
    city: [],
    country: [],
    zip_code: [],
    phone: [],
    location_id: [, Validators.required],
    latitude: [],
    longitude: [],
  });

  constructor(protected injector: Injector) {
    super(injector);
  }
}
