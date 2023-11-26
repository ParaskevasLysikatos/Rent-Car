import { Component, Injector } from '@angular/core';
import { PreviewComponent } from 'src/app/preview/preview.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { IRateCodeCollection } from '../rate-code-collection.interface';
import { RateCodeService } from '../rate-code.service';

@Component({
  selector: 'app-preview-rate-code',
  templateUrl: './preview-rate-code.component.html',
  styleUrls: ['./preview-rate-code.component.scss'],
  providers: [{provide: ApiService, useClass: RateCodeService}]
})
export class PreviewRateCodeComponent extends PreviewComponent<IRateCodeCollection> {
  displayedColumns = ['title','slug','actions'];

  constructor(protected injector: Injector,public rateCodeSrv:RateCodeService<IRateCodeCollection>) {
    super(injector);
    this.columns = [
      {
        columnDef: 'title',
        header: 'Τίτλος',
        cell: (element: IRateCodeCollection) => `${element.profiles?.el?.title}`,
        hasFilter: true,
      },
       {
        columnDef: 'slug',
        header: 'Σύνδεσμος',
        cell: (element: IRateCodeCollection) => `${element.slug}`
      }
    ];
  }
}
