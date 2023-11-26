import { Component, Injector, ViewChild } from '@angular/core';
import { EditFormComponent } from 'src/app/edit-form/edit-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { AgentFormComponent } from '../agent-form/agent-form.component';
import { IAgent } from '../agent.interface';
import { AgentService } from '../agent.service';

@Component({
  selector: 'app-edit-agent',
  templateUrl: './edit-agent.component.html',
  styleUrls: ['./edit-agent.component.scss'],
  providers: [{provide: ApiService, useClass: AgentService}]
})
export class EditAgentComponent extends EditFormComponent<IAgent> {
  @ViewChild(AgentFormComponent, {static: true}) formComponent!: AgentFormComponent;

  afterDataLoad(res:IAgent) {
    this.formComponent.loadSrv.loadingLight();
  this.formComponent.sub_agents_details = res.sub_agents_details;
  }
}
