import { Component, Injector, ViewChild } from '@angular/core';
import { EditFormComponent } from 'src/app/edit-form/edit-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { BrandFormComponent } from '../brand-form/brand-form.component';
import { IBrand } from '../brand.interface';
import { BrandService } from '../brand.service';

@Component({
  selector: 'app-edit-brand',
  templateUrl: './edit-brand.component.html',
  styleUrls: ['./edit-brand.component.scss'],
  providers: [{provide: ApiService, useClass: BrandService}]
})
export class EditBrandComponent extends EditFormComponent<IBrand> {
  @ViewChild(BrandFormComponent, {static: true}) formComponent!: BrandFormComponent;

  afterDataLoad(res:IBrand) {
  this.formComponent.iconData = res.icon;
  this.formComponent.printing_forms = res.print_form;
  this.formComponent.printing_forms.forEach((item:any)=> this.formComponent.addForm(
    item.print_form, item.placeholder_text_color,item.primary_background_color,
    item.primary_text_color,item.secondary_background_color,item.secondary_text_color))
    console.log(this.formComponent.form.controls.forms);
  }
}
