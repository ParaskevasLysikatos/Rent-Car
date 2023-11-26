import { AfterViewInit, Component, EventEmitter, Injector, Input, OnInit, Output, QueryList, ViewChild, ViewChildren } from '@angular/core';
import { AbstractFormComponent } from '../abstract-form/abstract-form.component';
import { LanguageService } from '../languages/language.service';
import { MultilingualTemplateComponent } from '../multilingual-template/multilingual-template.component';
import { Validators } from '@angular/forms';

interface TranslatedFields {
  [field: string]: string;
}
@Component({
  selector: 'app-multilingual-form',
  templateUrl: './multilingual-form.component.html',
  styleUrls: ['./multilingual-form.component.scss']
})
export abstract class MultilingualFormComponent extends AbstractFormComponent implements OnInit {
  @ViewChild(MultilingualTemplateComponent) template!: MultilingualTemplateComponent;
  translatedFields: TranslatedFields  = {
    title: 'Τίτλος',
    description: 'Περιγραφή'
  };
  langSrv: LanguageService;
  langs: any = [];
  currentLang!: string;

  constructor(protected injector: Injector) {
    super(injector);
    this.langSrv = injector.get(LanguageService);
  }

  ngOnInit(): void {
    this.langs = this.langSrv.getLangs();
    this.currentLang = this.langSrv.getCurrentLang();
    const langs = this.langSrv.getLangs();
    const groups: any = {};
    for (const lang of langs) {
      const group: any = {};
      for (const field of Object.keys(this.translatedFields)) {
        group[field] =[,Validators.required];
      }
      groups[lang] = this.fb.group(group)
    }
    this.form.addControl('profiles', this.fb.group(groups));
    // this.form.get('profiles.en').addValidators(Validators.required);
  }
}
