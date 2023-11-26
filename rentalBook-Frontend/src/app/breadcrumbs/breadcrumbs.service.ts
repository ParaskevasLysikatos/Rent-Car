import { HttpClient } from '@angular/common/http';
import { ElementRef, Injectable } from '@angular/core';
import { FormGroup } from '@angular/forms';
import { BehaviorSubject } from 'rxjs/internal/BehaviorSubject';
import { IPayers } from 'src/app/rental/rental-form/rental-form.component';
import { environment as env } from 'src/environments/environment';


@Injectable({
  providedIn: 'root'
})
export class BreadcrumbsService {

  TitleSubject: BehaviorSubject<string> = new BehaviorSubject(null);


  constructor() {

  }

}
