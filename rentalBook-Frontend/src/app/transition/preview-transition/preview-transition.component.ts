import { Component, Injector, TemplateRef, ViewChild } from '@angular/core';
import { MatOption } from '@angular/material/core';
import { MatSelect } from '@angular/material/select';
import { PreviewComponent } from 'src/app/preview/preview.component';
import { ITransitionType } from 'src/app/transition-type/transition-type.interface';
import { TransitionTypeService } from 'src/app/transition-type/transition-type.service';
import { ApiService } from 'src/app/_services/api-service.service';
import { ITransitionCollection } from '../transition-collection.interface';
import { ITransition } from '../transition.interface';
import { TransitionService } from '../transition.service';

@Component({
  selector: 'app-preview-transition',
  templateUrl: './preview-transition.component.html',
  styleUrls: ['./preview-transition.component.scss'],
  providers: [{provide: ApiService, useClass: TransitionService}]
})
export class PreviewTransitionComponent extends PreviewComponent<ITransitionCollection> {
  displayedColumns = ['licence_plates','user','type','from','to', 'actions'];
  @ViewChild('licence_plates_filter', { static: true }) licence_plates: TemplateRef<any>;
  @ViewChild('driver_filter', { static: true }) driver: TemplateRef<any>;
  @ViewChild('type_filter', { static: true }) type: TemplateRef<any>;

  @ViewChild('matRef') matRef: MatSelect;

  transitionTypeData: ITransitionType[];
  constructor(protected injector: Injector, protected transTypeSrv: TransitionTypeService<ITransitionType>,public transitionSrv: TransitionService<ITransitionCollection>) {
    super(injector);
  }

  ngOnInit() {
    super.ngOnInit();
    this.transTypeSrv.get({}, undefined, -1).subscribe((res) => { this.transitionTypeData = res.data });
    setTimeout(() => this.matRef.options.first.select(), 2000);//slow on initialize needs timeout
    this.columns = [
      {
        columnDef: 'licence_plates',
        header: 'Πινακίδα',
        cell: (element: ITransitionCollection) => `${element.vehicle?.licence_plates[0]?.licence_plate ?? '-'}`,
        hasFilter: true,
        filterTemplate: this.licence_plates,
      },
      {
        columnDef: 'user',
        header: 'Χρήστης',
        cell: (element: ITransitionCollection) => `${element.driver?.contact?.firstname ? element.driver?.contact?.firstname +' '+element.driver?.contact?.lastname+' ('+element.driver?.role +')' : '-' }`,
        hasFilter: true,
        filterTemplate: this.driver,
        filterField: 'driver_id',
      },
      {
        columnDef: 'type',
        header: 'Τύπος',
        cell: (element: ITransitionCollection) => `${element.type?.title}`,
        hasFilter: true,
        filterTemplate: this.type,
        filterField: 'type_id',
      },
       {
        columnDef: 'from',
        header: 'Εφυγε από',
        cell: (element: ITransitionCollection) => `${element.co_station?.title}`
      },
      {
        columnDef: 'to',
        header: 'Πήγε σε',
        cell: (element: ITransitionCollection) => `${element.ci_station?.title}`
      }

    ];
  }

  clear() {
    this.matRef.options.forEach((data: MatOption) => data.deselect());
  }
}
