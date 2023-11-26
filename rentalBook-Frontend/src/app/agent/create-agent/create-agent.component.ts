import { Component, Injector, ViewChild } from '@angular/core';
import { CreateFormComponent } from 'src/app/create-form/create-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { AgentFormComponent } from '../agent-form/agent-form.component';
import { IAgent } from '../agent.interface';
import { AgentService } from '../agent.service';

@Component({
  selector: 'app-create-agent',
  templateUrl: './create-agent.component.html',
  styleUrls: ['./create-agent.component.scss'],
  providers: [{provide: ApiService, useClass: AgentService}]
})
export class CreateAgentComponent extends CreateFormComponent<IAgent> {
  @ViewChild(AgentFormComponent, {static: true}) formComponent!: AgentFormComponent;


  ngOnInit(): void {
    super.ngOnInit();
    this.formComponent.loadSrv.loadingLight();
    //Called after the constructor, initializing input properties, and the first call to ngOnChanges.
    //Add 'implements OnInit' to the class.
  //  setTimeout(() => { this.formComponent.loadSrv.loadingOff(); }, 2000);
  }
}
