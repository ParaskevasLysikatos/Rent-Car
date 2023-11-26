import { Component, Input, OnInit } from '@angular/core';
import { ControlValueAccessor } from '@angular/forms';
import { MatFormFieldControl } from '@angular/material/form-field';
import { CreateDriverComponent } from 'src/app/driver/create-driver/create-driver.component';
import { IDriver } from 'src/app/driver/driver.interface';
import { DriverService } from 'src/app/driver/driver.service';
import { EditDriverComponent } from 'src/app/driver/edit-driver/edit-driver.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { AbstractAutocompleteSelectorComponent } from '../abstract-autocomplete-selector/abstract-autocomplete-selector.component';

@Component({
  selector: 'app-dr-cust-selector',
  templateUrl: './dr-cust-selector.component.html',
  styleUrls: ['./dr-cust-selector.component.scss'],
  providers: [
    { provide: MatFormFieldControl, useExisting: DrCustSelectorComponent },
    { provide: ApiService, useClass: DriverService }
  ]
})

export class DrCustSelectorComponent extends AbstractAutocompleteSelectorComponent<IDriver> implements MatFormFieldControl<any>,
  ControlValueAccessor {
  readonly EditComponent = EditDriverComponent;
  readonly CreateComponent = CreateDriverComponent;
  @Input() activeEditBtn: boolean = true;
  @Input() addBtn: boolean = true;
  @Input() phone: string = '';
  @Input() include: any[];
}
