import { Component, Injector, TemplateRef, ViewChild } from '@angular/core';
import { MatOption } from '@angular/material/core';
import { MatSelect } from '@angular/material/select';
import { PreviewComponent } from 'src/app/preview/preview.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { ICompanyCollection } from '../company-collection.interface';
import { ICompany } from '../company.interface';
import { CompanyService } from '../company.service';

@Component({
  selector: 'app-preview-company',
  templateUrl: './preview-company.component.html',
  styleUrls: ['./preview-company.component.scss'],
  providers: [{provide: ApiService, useClass: CompanyService}]
})
export class PreviewCompanyComponent extends PreviewComponent<ICompanyCollection> {
  displayedColumns = ['name', 'title', 'afm', 'phone','actions'];
  @ViewChild('foreign_afm_filter', { static: true }) foreign_afm: TemplateRef<any>;

  @ViewChild('matRef') matRef: MatSelect;

  constructor(protected injector: Injector,public companiesSrv:CompanyService<ICompany>) {
    super(injector);
  }
  ngOnInit() {
    super.ngOnInit();
    setTimeout(() => this.matRef.options.first.select(), 2000);//slow on initialize needs timeout
    this.columns = [
      {
        columnDef: 'name',
        header: 'Επωνυμία',
        cell: (element: ICompanyCollection) => `${element.name}`,
        hasFilter: true
      },
      {
        columnDef: 'title',
        header: 'Διακριτικός Τίτλος',
        cell: (element: ICompanyCollection) => `${element.title}`
      },
      {
        columnDef: 'afm',
        header: 'ΑΦΜ',
        cell: (element: ICompanyCollection) => `${element.afm}`,
        hasFilter: true
      },
      {
        columnDef: 'phone',
        header: 'Τηλέφωνο',
        cell: (element: ICompanyCollection) => `${element.phone}`,
        hasFilter: true
      },
      {
        columnDef: 'foreign_afm',
        header: 'Ξένο ΑΦΜ',
       // cell: (element: ICompanyCollection) => `${element.afm}`,
        hasFilter: true,
        filterTemplate: this.foreign_afm
      },

    ];
  }

  clear() {
    this.matRef.options.forEach((data: MatOption) => data.deselect());
  }

}
