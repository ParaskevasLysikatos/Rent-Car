import { Component, Injector, TemplateRef, ViewChild } from '@angular/core';
import { PreviewComponent } from 'src/app/preview/preview.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { IColorTypeCollection } from '../color-type-collection.interface';
import { IColorType } from '../color-type.interface';
import { ColorTypeService } from '../color-type.service';

@Component({
  selector: 'app-preview-color-type',
  templateUrl: './preview-color-type.component.html',
  styleUrls: ['./preview-color-type.component.scss'],
  providers: [{provide: ApiService, useClass: ColorTypeService}]
})
export class PreviewColorTypeComponent extends PreviewComponent<IColorTypeCollection> {
  displayedColumns = ['title','hex_code', 'actions'];
  @ViewChild('hex_code', { static: true }) hexCode: TemplateRef<any>;

  constructor(protected injector: Injector,public colorSrv:ColorTypeService<IColorType>) {
    super(injector);

  }
  ngOnInit(): void {
    super.ngOnInit();
    this.columns = [
       {
        columnDef: 'title',
        header: 'Τίτλος',
        cell: (element: IColorTypeCollection) => `${element.title}`,
        hasFilter: true,
      },
      {
        columnDef: 'hex_code',
        header: 'Χρώμα',
        cellTemplate: this.hexCode
      },
    ];
  }

}
