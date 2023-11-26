import { Component, Injector, TemplateRef, ViewChild } from '@angular/core';
import { LanguageService } from 'src/app/languages/language.service';
import { PreviewComponent } from 'src/app/preview/preview.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { ITypesCollection } from '../types-collection.interface';
import { ITypes } from '../types.interface';
import { TypesService } from '../types.service';

@Component({
  selector: 'app-preview-types',
  templateUrl: './preview-types.component.html',
  styleUrls: ['./preview-types.component.scss'],
  providers: [{provide: ApiService, useClass: TypesService}]
})
export class PreviewTypesComponent extends PreviewComponent<ITypesCollection> {
  displayedColumns = ['title','slug','category','characteristics','options','icon','actions'];

  @ViewChild('type_filter', { static: true }) typeFilter: TemplateRef<any>;
  constructor(protected injector: Injector,public typesSrv:TypesService<ITypes>) {
    super(injector);

    this.columns = [
      {
        columnDef: 'title',
        header: 'Τίτλος',
        cell: (element: ITypesCollection) => `${element.profiles?.el?.title}`,
        hasFilter: true,
      },
      {
        columnDef: 'slug',
        header: 'Σύνδεσμος',
        cell: (element: ITypesCollection) => `${element.slug}`
      },
      {
        columnDef: 'category',
        header: 'Κατηγορία',
        cell: (element: ITypesCollection) => `${element.category?.profiles?.el?.title}`,
        hasFilter: true,
      },
      {
        columnDef: 'characteristics',
        header: 'Χαρακτηριστικά',
        cell: (element: ITypesCollection) => `${element.characteristicsCount}`
      },
      {
        columnDef: 'options',
        header: 'Παροχές',
        cell: (element: ITypesCollection) => `${element.optionsCount}`
      },
      {
        columnDef: 'icon',
        header: 'Εικόνα',
        cell: (element: ITypesCollection) => `${element.imagesCount}`
      },
    ];
  }
}
