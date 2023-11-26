import { Component, Injector, ViewChild } from '@angular/core';
import { CreateFormComponent } from 'src/app/create-form/create-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { VisitFormComponent } from '../visit-form/visit-form.component';
import { IVisit } from '../visit.interface';
import { VisitService } from '../visit.service';

@Component({
  selector: 'app-create-visit',
  templateUrl: './create-visit.component.html',
  styleUrls: ['./create-visit.component.scss'],
  providers: [{provide: ApiService, useClass: VisitService}]
})
export class CreateVisitComponent extends CreateFormComponent<IVisit> {
  @ViewChild(VisitFormComponent, {static: true}) formComponent!: VisitFormComponent;
}
