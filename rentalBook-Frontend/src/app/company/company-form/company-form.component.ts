import { Component, Injector, OnInit } from '@angular/core';
import { Validators } from '@angular/forms';
import { AbstractFormComponent } from 'src/app/abstract-form/abstract-form.component';
import { IDriver } from 'src/app/driver/driver.interface';


@Component({
  selector: 'app-company-form',
  templateUrl: './company-form.component.html',
  styleUrls: ['./company-form.component.scss']
})
export class CompanyFormComponent extends AbstractFormComponent implements OnInit {
  form = this.fb.group({
    id: [],
    name:[,Validators.required],
    afm:[,Validators.required],
    doy:[],
    country:[],
    city:[],
    job:[],
    phone:[],
    email:[],
    website:[],
    title:[],
    address:[],
    comments:[],
    phone_2:[],
    zip_code:[],
    main:[],
    mite_number:[],
    foreign_afm:[],
    drivers:[],
  });

  rentals:any = [];
  bookings:any = [];
  quotes:any = [];
  invoices:any = [];

  constructor(protected injector: Injector) {
    super(injector);
  }
  ngOnInit() {
    super.ngOnInit();
  }

  ngAfterViewInit(): void {

  }

}
