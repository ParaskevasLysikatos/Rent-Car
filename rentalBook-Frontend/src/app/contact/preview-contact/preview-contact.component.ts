import { Component, Injector, TemplateRef, ViewChild } from '@angular/core';
import { PreviewComponent } from 'src/app/preview/preview.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { IContactCollection } from '../contact-collection.interface';
import { IContact } from '../contact.interface';
import { ContactService } from '../contact.service';

@Component({
  selector: 'app-preview-contact',
  templateUrl: './preview-contact.component.html',
  styleUrls: ['./preview-contact.component.scss'],
  providers: [{provide: ApiService, useClass: ContactService}]
})
export class PreviewContactComponent extends PreviewComponent<IContactCollection> {
  displayedColumns = ['firstname', 'lastname','actions'];
  @ViewChild('fullname_filter', { static: true }) fullname: TemplateRef<any>;

  constructor(protected injector: Injector, public contactSrv: ContactService<IContact>) {
    super(injector);
  }
  ngOnInit() {
    super.ngOnInit();
    this.columns = [
      {
        columnDef: 'firstname',
        header: 'Όνομα',
        cell: (element: IContactCollection) => `${element.firstname}`,
        hasFilter: true,
        filterTemplate: this.fullname,
        filterField: 'id',
      },
      {
        columnDef: 'lastname',
        header: 'Επώνυμο',
        cell: (element: IContactCollection) => `${element.lastname}`
      }
    ];
  }

}
