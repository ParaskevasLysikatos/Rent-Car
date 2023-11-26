import { Component, forwardRef, Injector } from '@angular/core';
import { NG_VALUE_ACCESSOR } from '@angular/forms';
import { CreateDriverComponent } from 'src/app/driver/create-driver/create-driver.component';
import { EditDriverComponent } from 'src/app/driver/edit-driver/edit-driver.component';
import { IDriver } from 'src/app/driver/driver.interface';
import { DriverService } from 'src/app/driver/driver.service';
import { AbstractSelectorComponent } from '../abstract-selector/abstract-selector.component';
import { ApiService } from 'src/app/_services/api-service.service';

@Component({
  selector: 'app-driver-selector',
  templateUrl: './driver-selector.component.html',
  styleUrls: ['./driver-selector.component.scss'],
  providers: [
    {
      provide: NG_VALUE_ACCESSOR,
      useExisting: forwardRef(() => DriverSelectorComponent),
      multi: true
    },
    {
      provide: AbstractSelectorComponent,
      useExisting: DriverSelectorComponent
    },
    {provide: ApiService, useClass: DriverService}
  ]
})
export class DriverSelectorComponent extends AbstractSelectorComponent<IDriver> {
  readonly EditComponent = EditDriverComponent;
  readonly CreateComponent = CreateDriverComponent;
  label = 'Οδηγός';
}
