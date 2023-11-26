import { Component, Injector, OnInit } from '@angular/core';
import { Validators } from '@angular/forms';
import { MultilingualFormComponent } from 'src/app/multilingual-form/multilingual-form.component';

@Component({
  selector: 'app-rate-code-form',
  templateUrl: './rate-code-form.component.html',
  styleUrls: ['./rate-code-form.component.scss']
})
export class RateCodeFormComponent extends MultilingualFormComponent implements OnInit {
  form = this.fb.group({
    id: null,
    slug: []
  });

  constructor(protected injector: Injector) {
    super(injector);
  }
}
