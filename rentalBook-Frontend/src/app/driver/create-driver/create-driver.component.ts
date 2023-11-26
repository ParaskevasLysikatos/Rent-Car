import { Component, Injector, Input, ViewChild } from '@angular/core';
import { CreateFormComponent } from 'src/app/create-form/create-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { DriverFormComponent } from '../driver-form/driver-form.component';
import { IDriver } from '../driver.interface';
import { DriverService } from '../driver.service';

@Component({
  selector: 'app-create-driver',
  templateUrl: './create-driver.component.html',
  styleUrls: ['./create-driver.component.scss'],
  providers: [{provide: ApiService, useClass: DriverService}]
})
export class CreateDriverComponent extends CreateFormComponent<IDriver> {
  @ViewChild(DriverFormComponent, {static: true}) formComponent!: DriverFormComponent;
  @Input() phoneDialog!: string;
  @Input() fullNameDialog!: string;

  ngOnInit() {
    super.ngOnInit();
    this.formComponent.customUrl = this.formComponent.urlSrv.url.split('/')[1] ?? '';
    if (this.formComponent.customUrl.match('quotes') || this.formComponent.customUrl.match('bookings')) { // if true comes through dialog(quote-booking)
      this.formComponent.form.controls.phone.patchValue(this.phoneDialog);
      let wordArray = this.fullNameDialog.split(' ');
      this.formComponent.form.controls.lastname.patchValue(wordArray[0]);
      wordArray.splice(0,1);
      this.formComponent.form.controls.firstname.patchValue(wordArray.join(' '));
    }

    if (this.formComponent.createRenSrv.driverSub.getValue()) {//comes from rental
      this.formComponent.form.controls.phone.patchValue(this.formComponent.createRenSrv.driverSub.getValue().phone);
      let wordArray = this.formComponent.createRenSrv.driverSub.getValue().name.split(' ');
      this.formComponent.form.controls.lastname.patchValue(wordArray[0]);
      wordArray.splice(0, 1);
      this.formComponent.form.controls.firstname.patchValue(wordArray.join(' '));
    }
  }





}
