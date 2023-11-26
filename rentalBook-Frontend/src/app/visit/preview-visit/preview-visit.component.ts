import { Component, Injector, TemplateRef, ViewChild } from '@angular/core';
import { PreviewComponent } from 'src/app/preview/preview.component';
import { IServiceDetails } from 'src/app/service-details/service-details.interface';
import { ServiceDetailsService } from 'src/app/service-details/service-details.service';
import { ApiService } from 'src/app/_services/api-service.service';
import { IVisitCollection } from '../visit-collection.interface';
import { VisitService } from '../visit.service';

@Component({
  selector: 'app-preview-visit',
  templateUrl: './preview-visit.component.html',
  styleUrls: ['./preview-visit.component.scss'],
  providers: [{provide: ApiService, useClass: VisitService}]
})
export class PreviewVisitComponent extends PreviewComponent<IVisitCollection> {
  displayedColumns = ['licence_plate', 'vehicle', 'km',
  'ladia', 'filtra_ladiou', 'filtra_aeros','filtra_kampinas',
    'takaki_empros', 'takaki_pisw',
    'psygeio_antipsyktiko','mpataria','balbolines',
    'mpouzi', 'imantas_xronisou', 'imantas_dyac','rouleman',
    'diskoi', 'symplektes','elastika'
  ,'date_start'];
  detailsData!: IServiceDetails[];
  @ViewChild('ladia', { static: true }) ladia: TemplateRef<any>;

  @ViewChild('filtra_ladiou', { static: true }) filtra_ladiou: TemplateRef<any>;
  @ViewChild('filtra_aeros', { static: true }) filtra_aeros: TemplateRef<any>;
  @ViewChild('filtra_kampinas', { static: true }) filtra_kampinas: TemplateRef<any>;

  @ViewChild('takaki_empros', { static: true }) takaki_empros: TemplateRef<any>;
  @ViewChild('takaki_pisw', { static: true }) takaki_pisw: TemplateRef<any>;

  @ViewChild('psygeio_antipsyktiko', { static: true }) psygeio_antipsyktiko: TemplateRef<any>;
  @ViewChild('mpataria', { static: true }) mpataria: TemplateRef<any>;
  @ViewChild('balbolines', { static: true }) balbolines: TemplateRef<any>;

  @ViewChild('mpouzi', { static: true }) mpouzi: TemplateRef<any>;
  @ViewChild('imantas_xronisou', { static: true }) imantas_xronisou: TemplateRef<any>;
  @ViewChild('imantas_dyac', { static: true }) imantas_dyac: TemplateRef<any>;
  @ViewChild('rouleman', { static: true }) rouleman: TemplateRef<any>;

  @ViewChild('diskoi', { static: true }) diskoi: TemplateRef<any>;
  @ViewChild('symplektes', { static: true }) symplektes: TemplateRef<any>;
  @ViewChild('elastika', { static: true }) elastika: TemplateRef<any>;

  constructor(protected injector: Injector, protected serviceDetailsSrv: ServiceDetailsService<IServiceDetails>,
              public visitSrv:VisitService<IVisitCollection>) {
    super(injector);
    //this.serviceDetailsSrv.get({},undefined,-1).subscribe(res => {this.detailsData=res.data.filter(s => s.category=='general')})
  }
  ngOnInit(): void {
    super.ngOnInit();
    this.columns = [
      {
        columnDef: 'licence_plate',
        header: 'Πινακίδα',
        cell: (element: IVisitCollection) => `${element.vehicle.licence_plates[0].licence_plate}`,
        hasFilter: true
      },
      {
        columnDef: 'vehicle',
        header: 'Αμάξι',
        cell: (element: IVisitCollection) => `${element.vehicle.make+' '+element.vehicle.model}`,
        hasFilter: true
      },
      {
        columnDef: 'km',
        header: 'Χιλιόμετρα',
        cell: (element: IVisitCollection) => `${element.km}`,
        sortBy: 'km',
      },
      {
        columnDef: 'date_start',
        header: 'Ημερομηνία',
        cell: (element: IVisitCollection) => `${element.date_start}`,
        sortBy: 'date_start',
      },
      {
        columnDef: 'ladia',
        header: 'Λάδια',
       // cell: (element: IVisitCollection) => `${element.date_start}`,
       cellTemplate:this.ladia
      },
      {
        columnDef: 'filtra_ladiou',
        header: 'Φίλτρα λαδιού',
        // cell: (element: IVisitCollection) => `${element.date_start}`,
        cellTemplate: this.filtra_ladiou
      },
      {
        columnDef: 'filtra_aeros',
        header: 'Φίλτρα αέρος',
        // cell: (element: IVisitCollection) => `${element.date_start}`,
        cellTemplate: this.filtra_aeros
      },
      {
        columnDef: 'filtra_kampinas',
        header: 'Φίλτρα καμπίνας',
        // cell: (element: IVisitCollection) => `${element.date_start}`,
        cellTemplate: this.filtra_kampinas
      },
      {
        columnDef: 'takaki_empros',
        header: 'Τακάκι εμπρός',
        // cell: (element: IVisitCollection) => `${element.date_start}`,
        cellTemplate: this.takaki_empros
      },
      {
        columnDef: 'takaki_pisw',
        header: 'Τακάκι πίσω',
        // cell: (element: IVisitCollection) => `${element.date_start}`,
        cellTemplate: this.takaki_pisw
      },
      {
        columnDef: 'psygeio_antipsyktiko',
        header: 'Ψυγείο Αντιψυκτικό',
        // cell: (element: IVisitCollection) => `${element.date_start}`,
        cellTemplate: this.psygeio_antipsyktiko
      },
      {
        columnDef: 'mpataria',
        header: 'Μπαταρία',
        // cell: (element: IVisitCollection) => `${element.date_start}`,
        cellTemplate: this.mpataria
      },
      {
        columnDef: 'balbolines',
        header: 'Βαλβολίνες',
        // cell: (element: IVisitCollection) => `${element.date_start}`,
        cellTemplate: this.balbolines
      },
      {
        columnDef: 'mpouzi',
        header: 'Μπουζί',
        // cell: (element: IVisitCollection) => `${element.date_start}`,
        cellTemplate: this.mpouzi
      },
      {
        columnDef: 'imantas_xronisou',
        header: 'Ιμάντας χρονισμού',
        // cell: (element: IVisitCollection) => `${element.date_start}`,
        cellTemplate: this.imantas_xronisou
      },
      {
        columnDef: 'imantas_dyac',
        header: 'Ιμάντας (Δ-Υ-AC)',
        // cell: (element: IVisitCollection) => `${element.date_start}`,
        cellTemplate: this.imantas_dyac
      },
      {
        columnDef: 'rouleman',
        header: 'Ρουλεμάν',
        // cell: (element: IVisitCollection) => `${element.date_start}`,
        cellTemplate: this.rouleman
      },
      {
        columnDef: 'diskoi',
        header: 'Δίσκοι (εμπρός-πίσω)',
        // cell: (element: IVisitCollection) => `${element.date_start}`,
        cellTemplate: this.diskoi
      },
      {
        columnDef: 'symplektes',
        header: 'Συμπλέκτες',
        // cell: (element: IVisitCollection) => `${element.date_start}`,
        cellTemplate: this.symplektes
      },
      {
        columnDef: 'elastika',
        header: 'Ελαστικά',
        // cell: (element: IVisitCollection) => `${element.date_start}`,
        cellTemplate: this.elastika
      },

    ];

      // this.serviceDetailsSrv.get({},undefined,-1).subscribe(res => {
      //   res.data.filter(s => s.category=='general').forEach(detail => {
      //     this.columns.push({
      //       columnDef:detail.slug,
      //       header:detail.title,
      //       cell:(element: IVisitCollection) => `${element.visit_details.filter(visit_detail => visit_detail.service_details_id==detail.id)[0]?.service_status.title.substring(0,1) ?? ''}`
      //     });
      //     this.displayedColumns.push(detail.slug);
      //   });
      //   //this.displayedColumns.push('actions');
      // })

    // console.log(this.columns);
    // console.log(this.displayedColumns);

  }





}
