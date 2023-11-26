import { Component, Injector, ViewChild } from '@angular/core';
import { FormControl } from '@angular/forms';
import { EditFormComponent } from 'src/app/edit-form/edit-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { VisitFormComponent } from '../visit-form/visit-form.component';
import { IVisit } from '../visit.interface';
import { VisitService } from '../visit.service';

@Component({
  selector: 'app-edit-visit',
  templateUrl: './edit-visit.component.html',
  styleUrls: ['./edit-visit.component.scss'],
  providers: [{provide: ApiService, useClass: VisitService}]
})
export class EditVisitComponent extends EditFormComponent<IVisit> {
  @ViewChild(VisitFormComponent, {static: true}) formComponent!: VisitFormComponent;

  afterDataLoad(res:IVisit) {
    this.formComponent.visitServiceDetails=res.visit_details;
    this.formComponent.visitVehicle=res.vehicle;
    this.formComponent.licence_plate=res.vehicle.licence_plates[0].licence_plate;

    this.formComponent.detailsData=this.formComponent.visitServiceDetails;

  }

}
