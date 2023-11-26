import { Component, Injector, ViewChild } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { ConfirmationDialogService } from 'src/app/confirmation-dialog/confirmation-dialog.service';
import { CreateFormComponent } from 'src/app/create-form/create-form.component';
import { SingleFormService } from 'src/app/single-form/single-form.service';
import { ApiService } from 'src/app/_services/api-service.service';
import { PlaceFormComponent } from '../place-form/place-form.component';
import { IPlace } from '../place.interface';
import { PlaceService } from '../place.service';

@Component({
  selector: 'app-create-place',
  templateUrl: './create-place.component.html',
  styleUrls: ['./create-place.component.scss'],
  providers: [{provide: ApiService, useClass: PlaceService}]
})
export class CreatePlaceComponent extends CreateFormComponent<IPlace> {
  @ViewChild(PlaceFormComponent, {static: true}) formComponent!: PlaceFormComponent;
}
