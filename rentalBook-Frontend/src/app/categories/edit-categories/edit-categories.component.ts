import { Component, Injector, ViewChild } from '@angular/core';
import { EditFormComponent } from 'src/app/edit-form/edit-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { CategoriesFormComponent } from '../categories-form/categories-form.component';
import { ICategories } from '../categories.interface';
import { CategoriesService } from '../categories.service';

@Component({
  selector: 'app-edit-categories',
  templateUrl: './edit-categories.component.html',
  styleUrls: ['./edit-categories.component.scss'],
  providers: [{provide: ApiService, useClass: CategoriesService}]
})
export class EditCategoriesComponent extends EditFormComponent<ICategories> {
  @ViewChild(CategoriesFormComponent, {static: true}) formComponent!: CategoriesFormComponent;

  afterDataLoad(res: ICategories) {
    this.formComponent.iconData = res.icon;
  }
}
