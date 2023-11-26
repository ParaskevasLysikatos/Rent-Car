import { Component, forwardRef, Injector } from '@angular/core';
import { NG_VALUE_ACCESSOR } from '@angular/forms';
import { CreateDriverComponent } from 'src/app/driver/create-driver/create-driver.component';
import { EditDriverComponent } from 'src/app/driver/edit-driver/edit-driver.component';
import { IDriver } from 'src/app/driver/driver.interface';
import { AbstractSelectorComponent } from '../abstract-selector/abstract-selector.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { DriverEmpService } from 'src/app/driver/driverEmp.service';

@Component({
  selector: 'app-driverEmp-selector',
  templateUrl: './driverEmp-selector.component.html',
  styleUrls: ['./driverEmp-selector.component.scss'],
  providers: [
    {
      provide: NG_VALUE_ACCESSOR,
      useExisting: forwardRef(() => DriverEmpSelectorComponent),
      multi: true
    },
    {
      provide: AbstractSelectorComponent,
      useExisting: DriverEmpSelectorComponent
    },
    {provide: ApiService, useClass: DriverEmpService}
  ]
})
export class DriverEmpSelectorComponent extends AbstractSelectorComponent<IDriver> {
  readonly EditComponent = EditDriverComponent;
  readonly CreateComponent = CreateDriverComponent;
  label = 'Υπάλληλος';
}
