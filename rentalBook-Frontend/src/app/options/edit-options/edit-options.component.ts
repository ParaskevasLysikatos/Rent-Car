import { Component, Injector, ViewChild } from '@angular/core';
import { EditFormComponent } from 'src/app/edit-form/edit-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { OptionsFormComponent } from '../options-form/options-form.component';
import { IOptions } from '../options.interface';
import { OptionsService } from '../options.service';

@Component({
  selector: 'app-edit-options',
  templateUrl: './edit-options.component.html',
  styleUrls: ['./edit-options.component.scss'],
  providers: [{provide: ApiService, useClass: OptionsService}]
})
export class EditOptionsComponent extends EditFormComponent<IOptions> {
  @ViewChild(OptionsFormComponent, {static: true}) formComponent!: OptionsFormComponent;
  type!: string|null;
  apiSrv!: OptionsService<IOptions>;
ngOnInit() {
    this.type = this.route.snapshot.paramMap.get('type');
    this.apiSrv.setType(this.type);
    super.ngOnInit();
  }

  ngOnDestroy() {
    this.apiSrv.setType(null);
  }
  afterDataLoad(res:IOptions){
    this.formComponent.boolActiveCost = this.formComponent.form.controls['active_daily_cost'].value;
    this.formComponent.iconData = res.icon;
  }
}
