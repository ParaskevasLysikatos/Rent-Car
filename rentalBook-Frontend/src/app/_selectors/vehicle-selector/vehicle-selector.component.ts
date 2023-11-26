import { Component, forwardRef, Injector, Input } from '@angular/core';
import { NG_VALUE_ACCESSOR } from '@angular/forms';
import { CreateVehicleComponent } from 'src/app/vehicle/create-vehicle/create-vehicle.component';
import { EditVehicleComponent } from 'src/app/vehicle/edit-vehicle/edit-vehicle.component';
import { IVehicle } from 'src/app/vehicle/vehicle.interface';
import { VehicleService } from 'src/app/vehicle/vehicle.service';
import { ApiService } from 'src/app/_services/api-service.service';
import { AbstractSelectorComponent } from '../abstract-selector/abstract-selector.component';

@Component({
  selector: 'app-vehicle-selector',
  templateUrl: './vehicle-selector.component.html',
  styleUrls: ['./vehicle-selector.component.scss'],
  providers: [
    {
      provide: NG_VALUE_ACCESSOR,
      useExisting: forwardRef(() => VehicleSelectorComponent),
      multi: true
    },
    {
      provide: AbstractSelectorComponent,
      useExisting: VehicleSelectorComponent
    },
    {provide: ApiService, useClass: VehicleService}
  ]
})
export class VehicleSelectorComponent extends AbstractSelectorComponent<IVehicle> {
  readonly EditComponent = EditVehicleComponent;
  readonly CreateComponent = CreateVehicleComponent;
  label = 'Πινακίδα';
  // private _fromDate!: Date;
  // private _toDate!: Date;
  // @Input() set fromDate(date: Date) {
  //   this._fromDate = date;
  //   this.dateChange();
  // }
  // @Input() set toDate(date: Date) {
  //   this._toDate = date;
  //   this.dateChange();
  // }
  // @Input() rentalId!: string;

  // get fromDate() {
  //   return this._fromDate;
  // }

  // get toDate() {
  //   return this._toDate;
  // }

  // ngOnInit() {
  //   super.ngOnInit();
  //   this.params.editBtn = false;

  //   this.selector.getFilters = (filters: any) => {
  //     filters.from = this.fromDate;
  //     filters.to = this.toDate;

  //     if (this.rentalId) {
  //       filters.rental_id = this.rentalId;
  //     }

  //     return filters;
  //   };
  // }

  // dateChange(): void {
  //   if (this.selector) {
  //     this.selector.data = [];
  //     const selectedIds = this.selector.selectedOptions.map(option => option.id);
  //     const filters = this.selector.getFilters({
  //       id: selectedIds
  //     });
  //     if (selectedIds.length > 0) {
  //       this.apiSrv.get(filters, undefined, -1).subscribe(res => {
  //         this.selector.selectControl.patchValue(this.selector.selectedOptions.filter(option =>
  //           res.data.map(model => model.id).includes(option.id)));
  //       });
  //     }
  //   }
  // }
}
