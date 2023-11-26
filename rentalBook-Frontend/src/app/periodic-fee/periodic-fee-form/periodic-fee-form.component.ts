import { Component, Injector, OnInit } from '@angular/core';
import { Validators } from '@angular/forms';
import { AbstractFormComponent } from 'src/app/abstract-form/abstract-form.component';
import { MultilingualFormComponent } from 'src/app/multilingual-form/multilingual-form.component';
import { IPeriodicFeeTypes } from 'src/app/periodic-fee-types/periodic-fee-types.interface';
import { PeriodicFeeTypesService } from 'src/app/periodic-fee-types/periodic-fee-types.service';

@Component({
  selector: 'app-periodic-fee-form',
  templateUrl: './periodic-fee-form.component.html',
  styleUrls: ['./periodic-fee-form.component.scss']
})
export class PeriodicFeeFormComponent extends AbstractFormComponent implements OnInit {
  form = this.fb.group({
    id: [],
    periodic_fee_type_id:[,Validators.required],
    vehicle_id: [],
    title: [,Validators.required],
    description: [],
    fee: [],
    date_start: [,Validators.required],
    date_expiration: [,Validators.required],
    date_payed: [],
    fee_type: [],
    documents: [],
  });

  constructor(protected injector: Injector,private pfSrv:PeriodicFeeTypesService<IPeriodicFeeTypes>) {
    super(injector);
  }

  periodicTypes!: any[];
  ngOnInit() {
    this.pfSrv.get({}, undefined, -1).subscribe(res => this.periodicTypes = res.data);
    this.form.controls.periodic_fee_type_id.valueChanges.subscribe(id=>{this.form.controls.fee_type.patchValue(this.periodicTypes.find(periodicType=>periodicType.id==id))})
  }

}
