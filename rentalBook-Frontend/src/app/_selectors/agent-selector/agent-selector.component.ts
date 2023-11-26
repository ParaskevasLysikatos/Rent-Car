import { Component, forwardRef, Injector, Input} from '@angular/core';
import { NG_VALUE_ACCESSOR } from '@angular/forms';
import { CreateAgentComponent } from 'src/app/agent/create-agent/create-agent.component';
import { EditAgentComponent } from 'src/app/agent/edit-agent/edit-agent.component';
import { IAgent } from 'src/app/agent/agent.interface';
import { AgentService } from 'src/app/agent/agent.service';
import { AbstractSelectorComponent } from '../abstract-selector/abstract-selector.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { AgentSelectorService } from 'src/app/subaccount/agentSelector.service';

@Component({
  selector: 'app-agent-selector',
  templateUrl: './agent-selector.component.html',
  styleUrls: ['./agent-selector.component.scss'],
  providers: [
    {
      provide: NG_VALUE_ACCESSOR,
      useExisting: forwardRef(() => AgentSelectorComponent),
      multi: true
    },
    {
      provide: AbstractSelectorComponent,
      useExisting: AgentSelectorComponent
    },
    { provide: ApiService, useClass: AgentSelectorService}
  ]
})
export class AgentSelectorComponent extends AbstractSelectorComponent<IAgent> {
  readonly EditComponent = EditAgentComponent;
  readonly CreateComponent = CreateAgentComponent;
  label = 'Πράκτορας';
}
