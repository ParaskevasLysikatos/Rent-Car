import { Component, ContentChildren, HostListener, Input, OnInit, TemplateRef, ViewChild } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { MatTab, MatTabGroup } from '@angular/material/tabs';
import { LanguageService } from '../languages/language.service';

interface TranslatedFields {
  [field: string]: string;
}

@Component({
  selector: 'app-multilingual-template',
  templateUrl: './multilingual-template.component.html',
  styleUrls: ['./multilingual-template.component.scss']
})
export class MultilingualTemplateComponent implements OnInit {
  @Input() formGroup!: FormGroup;
  @Input() multiLingualTemplate!: TemplateRef<any>;
  @Input() translatedFields: TranslatedFields  = {
    title: 'Τίτλος',
    description: 'Περιγραφή'
  };
  @ViewChild(MatTabGroup, {static: true}) matTabGroup!: MatTabGroup;
  @ContentChildren(MatTab) matTabs: MatTab[] = [];
  langs: Array<any> = [];
  currentLang!: string;

  constructor(protected fb: FormBuilder, protected langSrv: LanguageService) {
  }

  ngOnInit(): void {
    this.langs = this.langSrv.getLangs();
    this.currentLang = this.langSrv.getCurrentLang();
   // console.log(this.formGroup)
  }

  @HostListener('document:click', ['$event'])
  handleEventChange(event: Event) {
   // console.log(this.formGroup);
  }

}
