import { Component, Injector, ViewChild } from '@angular/core';
import { CreateFormComponent } from 'src/app/create-form/create-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { LanguagesFormComponent } from '../languages-form/languages-form.component';
import { ILanguages } from '../languages.interface';
import { LanguagesService } from '../languages.service';

@Component({
  selector: 'app-create-languages',
  templateUrl: './create-languages.component.html',
  styleUrls: ['./create-languages.component.scss'],
  providers: [{provide: ApiService, useClass: LanguagesService}]
})
export class CreateLanguagesComponent extends CreateFormComponent<ILanguages> {
  @ViewChild(LanguagesFormComponent, {static: true}) formComponent!: LanguagesFormComponent;
}
