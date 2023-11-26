import { Component, Injector, OnInit, ViewChild } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { ConfirmationDialogService } from 'src/app/confirmation-dialog/confirmation-dialog.service';
import { CreateFormComponent } from 'src/app/create-form/create-form.component';
import { SingleFormService } from 'src/app/single-form/single-form.service';
import { ApiService } from 'src/app/_services/api-service.service';
import { LocationFormComponent } from '../location-form/location-form.component';
import { ILocation } from '../location.inteface';
import { LocationService } from '../location.service';

@Component({
  selector: 'app-create-location',
  templateUrl: './create-location.component.html',
  styleUrls: ['./create-location.component.scss'],
  providers: [{provide: ApiService, useClass: LocationService}]
})
export class CreateLocationComponent extends CreateFormComponent<ILocation> {
  @ViewChild(LocationFormComponent, {static: true}) formComponent!: LocationFormComponent;
}
