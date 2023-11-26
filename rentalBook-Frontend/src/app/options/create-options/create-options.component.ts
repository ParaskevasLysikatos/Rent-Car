import { Component, Injector, ViewChild } from '@angular/core';
import { CreateFormComponent } from 'src/app/create-form/create-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { OptionsFormComponent } from '../options-form/options-form.component';
import { IOptions } from '../options.interface';
import { OptionsService } from '../options.service';

@Component({
  selector: 'app-create-options',
  templateUrl: './create-options.component.html',
  styleUrls: ['./create-options.component.scss'],
  providers: [{provide: ApiService, useClass: OptionsService}]
})
export class CreateOptionsComponent extends CreateFormComponent<IOptions> {
  @ViewChild(OptionsFormComponent, {static: true}) formComponent!: OptionsFormComponent;
}
