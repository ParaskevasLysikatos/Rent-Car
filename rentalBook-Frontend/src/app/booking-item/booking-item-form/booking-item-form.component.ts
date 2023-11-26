import { Component, Injector, OnInit } from '@angular/core';
import { Validators } from '@angular/forms';
import { MultilingualFormComponent } from 'src/app/multilingual-form/multilingual-form.component';

@Component({
  selector: 'app-booking-item-form',
  templateUrl: './booking-item-form.component.html',
  styleUrls: ['./booking-item-form.component.scss']
})
export class BookingItemFormComponent extends MultilingualFormComponent implements OnInit {
  form = this.fb.group({
    id: null
  });

  constructor(protected injector: Injector) {
    super(injector);
  }
}
