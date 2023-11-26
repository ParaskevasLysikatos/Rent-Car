import { Component, ViewChild } from '@angular/core';
import { CreateFormComponent } from 'src/app/create-form/create-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { PeriodicFeeFormComponent } from '../periodic-fee-form/periodic-fee-form.component';
import { IPeriodicFee } from '../periodic-fee.interface';
import { PeriodicFeeService } from '../periodic-fee.service';

@Component({
  selector: 'app-create-periodic-fee',
  templateUrl: './create-periodic-fee.component.html',
  styleUrls: ['./create-periodic-fee.component.scss'],
  providers: [{provide: ApiService, useClass: PeriodicFeeService}]
})
export class CreatePeriodicFeeComponent extends CreateFormComponent<IPeriodicFee> {
  @ViewChild(PeriodicFeeFormComponent, {static: true}) formComponent!: PeriodicFeeFormComponent;
}
