import { Component, Injector, OnInit } from '@angular/core';
import { Validators } from '@angular/forms';
import { MultilingualFormComponent } from 'src/app/multilingual-form/multilingual-form.component';

@Component({
  selector: 'app-document-type-form',
  templateUrl: './document-type-form.component.html',
  styleUrls: ['./document-type-form.component.scss']
})
export class DocumentTypeFormComponent extends MultilingualFormComponent implements OnInit {
  form = this.fb.group({
    id: null
  });

  constructor(protected injector: Injector) {
    super(injector);
  }
}
