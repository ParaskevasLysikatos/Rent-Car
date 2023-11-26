import { Injectable, Injector } from '@angular/core';
import { BehaviorSubject } from 'rxjs';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { IAgentCollection } from './agent-collection.interface';
import { IAgent } from './agent.interface';

@Injectable({
  providedIn: 'root'
})
export class AgentService<T extends IAgent> extends ApiService<T> {
  url = `${env.apiUrl}/agents`;

  total_AgentSub: BehaviorSubject<IAgentCollection> = new BehaviorSubject(null);

  constructor(protected injector: Injector) {
    super(injector);
  }
}
