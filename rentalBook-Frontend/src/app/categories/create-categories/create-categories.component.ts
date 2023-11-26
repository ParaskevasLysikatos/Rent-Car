import { Component, Injector, ViewChild } from '@angular/core';
import { CreateFormComponent } from 'src/app/create-form/create-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { CategoriesFormComponent } from '../categories-form/categories-form.component';
import { ICategories } from '../categories.interface';
import { CategoriesService } from '../categories.service';

@Component({
  selector: 'app-create-categories',
  templateUrl: './create-categories.component.html',
  styleUrls: ['./create-categories.component.scss'],
  providers: [{provide: ApiService, useClass: CategoriesService}]
})
export class CreateCategoriesComponent extends CreateFormComponent<ICategories> {
  @ViewChild(CategoriesFormComponent, {static: true}) formComponent!: CategoriesFormComponent;
}
