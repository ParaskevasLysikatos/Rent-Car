import { Component, Injector, TemplateRef, ViewChild } from '@angular/core';
import { PreviewComponent } from 'src/app/preview/preview.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { ICategoriesCollection } from '../categories-collection.interface';
import { CategoriesService } from '../categories.service';

@Component({
  selector: 'app-preview-categories',
  templateUrl: './preview-categories.component.html',
  styleUrls: ['./preview-categories.component.scss'],
  providers: [{provide: ApiService, useClass: CategoriesService}]
})
export class PreviewCategoriesComponent extends PreviewComponent<ICategoriesCollection> {
  displayedColumns = ['profiles','slug', 'icon','actions'];
  @ViewChild('Icon', { static: true }) icon: TemplateRef<any>;


  constructor(protected injector: Injector,public categoriesSrv:CategoriesService<ICategoriesCollection>) {
    super(injector);
  }
  ngOnInit(): void{
    super.ngOnInit();
    this.columns = [
      {
        columnDef: 'profiles',
        header: 'Κατηγορία',
        cell: (element: ICategoriesCollection) => `${element.profiles.el.title}`,
         hasFilter: true,
        filterField: 'title',
      },
      {
        columnDef: 'slug',
        header: 'Σύνδεσμος',
        cell: (element: ICategoriesCollection) => `${element.slug}`,
        hasFilter: true,
      },
       {
        columnDef: 'icon',
        header: 'Εικονίδιο',
        // cell: (element: ICharacteristicsCollection) => `${element.icon}`,
        cellTemplate: this.icon
      }
    ];
  }
}
