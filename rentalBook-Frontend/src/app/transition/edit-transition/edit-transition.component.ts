import { Component, ViewChild } from '@angular/core';
import { EditFormComponent } from 'src/app/edit-form/edit-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { TransitionFormComponent } from '../transition-form/transition-form.component';
import { ITransition } from '../transition.interface';
import { TransitionService } from '../transition.service';
import moment from 'moment';

@Component({
  selector: 'app-edit-transition',
  templateUrl: './edit-transition.component.html',
  styleUrls: ['./edit-transition.component.scss'],
  providers: [{provide: ApiService, useClass: TransitionService}]
})
export class EditTransitionComponent extends EditFormComponent<ITransition> {
  @ViewChild(TransitionFormComponent, {static: true}) formComponent!: TransitionFormComponent;

   afterDataLoad(res:ITransition) {
    this.formComponent.allTransitionData=res;
     this.formComponent.stationCI_id = res.ci_station_id;
     this.formComponent.stationCO_id = res.co_station_id;

     let dateCi = res.ci_datetime;
     let timeCi: string = String(this.formComponent.datetimeCI.timepickerControl.value);
     // this.formComponent.datetime.timepickerControl.patchValue(moment(date).hour(+String(time).substring(0, 2)).minute(+String(time).substring(3, 6)).format('HH:mm'));
     this.formComponent.form.controls.ci_datetime.patchValue(moment(dateCi).hour(+String(timeCi.substring(0, 2))).minute(+String(timeCi.substring(3, 6))).format('YYYY-MM-DD HH:mm'));
     setTimeout(() => this.formComponent.datetimeCI.timepickerControl.patchValue(moment(dateCi).hour(+String(timeCi.substring(0, 2))).minute(+String(timeCi.substring(3, 6))).format('HH:mm')), 200);


     let dateCo = res.co_datetime;
     let timeCo: string = String(this.formComponent.datetimeCO.timepickerControl.value);
     // this.formComponent.datetime.timepickerControl.patchValue(moment(date).hour(+String(time).substring(0, 2)).minute(+String(time).substring(3, 6)).format('HH:mm'));
     this.formComponent.form.controls.co_datetime.patchValue(moment(dateCo).hour(+String(timeCo.substring(0, 2))).minute(+String(timeCo.substring(3, 6))).format('YYYY-MM-DD HH:mm'));
     setTimeout(() => this.formComponent.datetimeCO.timepickerControl.patchValue(moment(dateCo).hour(+String(timeCo.substring(0, 2))).minute(+String(timeCo.substring(3, 6))).format('HH:mm')), 200);

    // console.log(this.formComponent.allTransitionData.driver.role);
     if (this.formComponent.allTransitionData?.driver.role == 'customer') {
       this.formComponent.boolDriverRole = true;
       this.formComponent.custRB.checked = true;
     }
     else {
       this.formComponent.boolDriverRole = false;
       this.formComponent.empRB.checked = true;
     }
  }



}
