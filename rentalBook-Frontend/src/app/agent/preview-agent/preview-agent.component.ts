import { Component, Injector, TemplateRef, ViewChild } from '@angular/core';
import { PreviewComponent } from 'src/app/preview/preview.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { IAgentCollection } from '../agent-collection.interface';
import { AgentService } from '../agent.service';

@Component({
  selector: 'app-preview-agent',
  templateUrl: './preview-agent.component.html',
  styleUrls: ['./preview-agent.component.scss'],
  providers: [{provide: ApiService, useClass: AgentService}]
})
export class PreviewAgentComponent extends PreviewComponent<IAgentCollection> {
  displayedColumns = ['name', 'booking_source', 'program', 'phone','mobile','commission' ,'actions'];
  @ViewChild('name_filter', { static: true }) name: TemplateRef<any>;

  constructor(protected injector: Injector,public agentSrv: AgentService<IAgentCollection>) {
    super(injector);
  }
  ngOnInit() {
    super.ngOnInit();
    this.columns = [
      {
        columnDef: 'name',
        header: 'Όνομα',
        cell: (element: IAgentCollection) => `${element.name}`,
        hasFilter: true,
        filterTemplate: this.name,
        filterField:'id'
      },
      {
        columnDef: 'booking_source',
        header: 'Πηγή',
        cell: (element: IAgentCollection) => `${element.booking_source?.profiles?.el?.title ?? ''}`
      },
      {
        columnDef: 'phone',
        header: 'Τηλέφωνο',
        cell: (element: IAgentCollection) => `${element.contact_information?.phone ?? ''}`
      },
      {
        columnDef: 'mobile',
        header: 'Κηνιτό',
        cell: (element: IAgentCollection) => `${element.contact_information?.mobile ?? ''}`
      },
      {
        columnDef: 'program',
        header: 'Πρόγραμμα',
        cell: (element: IAgentCollection) => `${element.program?.profiles?.el?.title ?? ''}`
      },
      {
        columnDef: 'commission',
        header: 'Προμήθεια',
        cell: (element: IAgentCollection) => `${element.commission}`
      },
    ];
  }

}
