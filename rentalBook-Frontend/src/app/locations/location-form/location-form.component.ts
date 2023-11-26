import { Component, Injector, OnChanges, OnInit } from '@angular/core';
import { FormBuilder, Validators } from '@angular/forms';
import { LanguageService } from 'src/app/languages/language.service';
import { MultilingualFormComponent } from 'src/app/multilingual-form/multilingual-form.component';

@Component({
  selector: 'app-location-form',
  templateUrl: './location-form.component.html',
  styleUrls: ['./location-form.component.scss']
})
export class LocationFormComponent extends MultilingualFormComponent implements OnInit {
  form = this.fb.group({
    id: null,
    latitude: [null, Validators.required],
    longitude: [null, Validators.required]
  });
  translatedFields = {
    title: 'Τίτλος'
  };

  constructor(protected injector: Injector) {
    super(injector);
  }
}
