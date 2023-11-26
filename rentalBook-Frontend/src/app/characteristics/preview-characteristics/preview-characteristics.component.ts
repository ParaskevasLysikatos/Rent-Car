import { Component, Injector, TemplateRef, ViewChild } from '@angular/core';
import { LanguageService } from 'src/app/languages/language.service';
import { PreviewComponent } from 'src/app/preview/preview.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { ICharacteristicsCollection } from '../characteristics-collection.interface';
import { CharacteristicsService } from '../characteristics.service';

@Component({
  selector: 'app-preview-characteristics',
  templateUrl: './preview-characteristics.component.html',
  styleUrls: ['./preview-characteristics.component.scss'],
  providers: [{provide: ApiService, useClass: CharacteristicsService}]
})
export class PreviewCharacteristicsComponent extends PreviewComponent<ICharacteristicsCollection> {
  displayedColumns = ['profiles', 'slug','icon', 'actions'];
  @ViewChild('Icon', { static: true }) icon: TemplateRef<any>;

  constructor(protected injector: Injector,public charSrv: CharacteristicsService<ICharacteristicsCollection>) {
    super(injector);
  }

ngOnInit(): void {
  super.ngOnInit();
    this.columns = [
      {
        columnDef: 'profiles',
        header: 'Κατηγορία',
        cell: (element: ICharacteristicsCollection) => `${element.profiles?.el?.title}`,
        hasFilter: true,
        filterField: 'title',
      },
      {
        columnDef: 'slug',
        header: 'Σύνδεσμος',
        cell: (element: ICharacteristicsCollection) => `${element.slug}`,
        hasFilter: true,
      },
      {
        columnDef: 'icon',
        header: 'Εικονίδιο',
      // cell: (element: ICharacteristicsCollection) => `${element.icon}`,
      cellTemplate: this.icon
      },
    ];
  }

}
