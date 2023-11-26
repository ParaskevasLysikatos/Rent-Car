import { Component, Injector, ViewChild } from '@angular/core';
import { EditFormComponent } from 'src/app/edit-form/edit-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { LanguagesFormComponent } from '../languages-form/languages-form.component';
import { ILanguages } from '../languages.interface';
import { LanguagesService } from '../languages.service';

@Component({
  selector: 'app-edit-languages',
  templateUrl: './edit-languages.component.html',
  styleUrls: ['./edit-languages.component.scss'],
  providers: [{provide: ApiService, useClass: LanguagesService}]
})
export class EditLanguagesComponent extends EditFormComponent<ILanguages> {
  @ViewChild(LanguagesFormComponent, {static: true}) formComponent!: LanguagesFormComponent;
}
