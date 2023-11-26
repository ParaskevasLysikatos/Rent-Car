import { Component, forwardRef, Injector } from '@angular/core';
import { NG_VALUE_ACCESSOR } from '@angular/forms';
import { CreateBrandComponent } from 'src/app/brand/create-brand/create-brand.component';
import { EditBrandComponent } from 'src/app/brand/edit-brand/edit-brand.component';
import { IBrand } from 'src/app/brand/brand.interface';
import { BrandService } from 'src/app/brand/brand.service';
import { AbstractSelectorComponent } from '../abstract-selector/abstract-selector.component';
import { ApiService } from 'src/app/_services/api-service.service';

@Component({
  selector: 'app-brand-selector',
  templateUrl: './brand-selector.component.html',
  styleUrls: ['./brand-selector.component.scss'],
  providers: [
    {
      provide: NG_VALUE_ACCESSOR,
      useExisting: forwardRef(() => BrandSelectorComponent),
      multi: true
    },
    {
      provide: AbstractSelectorComponent,
      useExisting: BrandSelectorComponent
    },
    {provide: ApiService, useClass: BrandService}
  ]
})
export class BrandSelectorComponent extends AbstractSelectorComponent<IBrand> {
  readonly EditComponent = EditBrandComponent;
  readonly CreateComponent = CreateBrandComponent;
  label = 'Brand';
}
