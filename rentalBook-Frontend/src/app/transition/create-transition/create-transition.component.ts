import { Component, ElementRef, ViewChild } from '@angular/core';
import { MatRadioButton } from '@angular/material/radio';
import { CreateFormComponent } from 'src/app/create-form/create-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { TransitionFormComponent } from '../transition-form/transition-form.component';
import { ITransition } from '../transition.interface';
import { TransitionService } from '../transition.service';
import moment from 'moment';

@Component({
  selector: 'app-create-transition',
  templateUrl: './create-transition.component.html',
  styleUrls: ['./create-transition.component.scss'],
  providers: [{provide: ApiService, useClass: TransitionService}]
})
export class CreateTransitionComponent extends CreateFormComponent<ITransition> {
  @ViewChild(TransitionFormComponent, {static: true}) formComponent!: TransitionFormComponent;

  currentDate = moment().format('YYYY-MM-DD HH:mm');

  ngOnInit(){
    super.ngOnInit();
    this.formComponent.form.controls.co_datetime.patchValue(this.currentDate);
    this.formComponent.form.controls.ci_datetime.patchValue(this.currentDate);
    let currentUser = JSON.parse(localStorage.getItem('loggedUser'));
    //this.formComponent.userSrv.authSrv.user.subscribe((res) => {
    this.formComponent.form.controls.co_station_id.patchValue(currentUser.station_id);
    this.formComponent.form.controls.ci_station_id.patchValue(currentUser.station_id);
      this.formComponent.stationCI_id = this.formComponent.form.controls.ci_station_id.value;
      this.formComponent.stationCO_id = this.formComponent.form.controls.ci_station_id.value;
    this.formComponent.form.controls.co_user_id.patchValue(currentUser.id);
    this.formComponent.form.controls.ci_user_id.patchValue(currentUser.id);
    //});
  }



}
