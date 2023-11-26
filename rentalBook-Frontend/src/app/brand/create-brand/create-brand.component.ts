import { Component, Injector, ViewChild } from '@angular/core';
import { CreateFormComponent } from 'src/app/create-form/create-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { BrandFormComponent } from '../brand-form/brand-form.component';
import { IBrand } from '../brand.interface';
import { BrandService } from '../brand.service';

@Component({
  selector: 'app-create-brand',
  templateUrl: './create-brand.component.html',
  styleUrls: ['./create-brand.component.scss'],
  providers: [{provide: ApiService, useClass: BrandService}]
})
export class CreateBrandComponent extends CreateFormComponent<IBrand> {
  @ViewChild(BrandFormComponent, {static: true}) formComponent!: BrandFormComponent;

  printing_forms2: any[] = ['quote','booking','rental','invoice','receipt','payment','refund','pre-auth','refund-pre-auth'];

  ngOnInit(): void {
      super.ngOnInit();
    this.printing_forms2.forEach((item: any) => this.formComponent.addForm(
      item, '', '',
      '', '', ''));
      console.log(this.formComponent.form.controls.forms);
  }


}
