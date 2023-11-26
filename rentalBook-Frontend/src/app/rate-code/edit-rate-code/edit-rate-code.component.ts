import { Component, Injector, ViewChild } from '@angular/core';
import { EditFormComponent } from 'src/app/edit-form/edit-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { RateCodeFormComponent } from '../rate-code-form/rate-code-form.component';
import { IRateCode } from '../rate-code.interface';
import { RateCodeService } from '../rate-code.service';

@Component({
  selector: 'app-edit-rate-code',
  templateUrl: './edit-rate-code.component.html',
  styleUrls: ['./edit-rate-code.component.scss'],
  providers: [{provide: ApiService, useClass: RateCodeService}]
})
export class EditRateCodeComponent extends EditFormComponent<IRateCode> {
  @ViewChild(RateCodeFormComponent, {static: true}) formComponent!: RateCodeFormComponent;
}
