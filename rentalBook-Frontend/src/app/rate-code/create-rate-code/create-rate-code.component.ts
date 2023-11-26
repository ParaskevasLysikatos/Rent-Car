import { Component, Injector, ViewChild } from '@angular/core';
import { CreateFormComponent } from 'src/app/create-form/create-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { RateCodeFormComponent } from '../rate-code-form/rate-code-form.component';
import { IRateCode } from '../rate-code.interface';
import { RateCodeService } from '../rate-code.service';

@Component({
  selector: 'app-create-rate-code',
  templateUrl: './create-rate-code.component.html',
  styleUrls: ['./create-rate-code.component.scss'],
  providers: [{provide: ApiService, useClass: RateCodeService}]
})
export class CreateRateCodeComponent extends CreateFormComponent<IRateCode> {
  @ViewChild(RateCodeFormComponent, {static: true}) formComponent!: RateCodeFormComponent;
}
