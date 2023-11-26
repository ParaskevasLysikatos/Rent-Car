import { Component, Injector, TemplateRef, ViewChild } from '@angular/core';
import { PreviewComponent } from 'src/app/preview/preview.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { IBrandCollection } from '../brand-collection.interface';
import { IBrand } from '../brand.interface';
import { BrandService } from '../brand.service';

@Component({
  selector: 'app-preview-brand',
  templateUrl: './preview-brand.component.html',
  styleUrls: ['./preview-brand.component.scss'],
  providers: [{provide: ApiService, useClass: BrandService}]
})
export class PreviewBrandComponent extends PreviewComponent<IBrandCollection> {
  displayedColumns = ['title','slug', 'icon','actions'];
  @ViewChild('Icon,', { static: true }) icon: TemplateRef<any>;

  constructor(protected injector: Injector,public brandSrv:BrandService<IBrand>) {
    super(injector);
  }
  ngOnInit(): void {
    super.ngOnInit();
    this.columns = [
      {
        columnDef: 'title',
        header: 'Τίτλος',
        cell: (element: IBrandCollection) => `${element.profiles?.el?.title}`,
        hasFilter: true,
      },
       {
        columnDef: 'slug',
        header: 'Σύνδεσμος',
        cell: (element: IBrandCollection) => `${element.slug}`
      },
        {
        columnDef: 'icon',
        header: 'Εικονίδιο',
          cellTemplate: this.icon
      }
    ];
  }
}
