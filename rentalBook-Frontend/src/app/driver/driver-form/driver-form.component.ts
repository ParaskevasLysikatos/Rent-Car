import { Component, Injector, OnInit } from '@angular/core';
import { Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { AbstractFormComponent } from 'src/app/abstract-form/abstract-form.component';
import { MyLoadingService } from 'src/app/my-loading/my-loading.service';
import moment from 'moment';
import { CreateRentalModalService } from 'src/app/booking/create-rental-modal/create-rental-modal.service';
import { NotificationService } from 'src/app/_services/notification.service';
@Component({
  selector: 'app-driver-form',
  templateUrl: './driver-form.component.html',
  styleUrls: ['./driver-form.component.scss']
})
export class DriverFormComponent extends AbstractFormComponent implements OnInit {
  form = this.fb.group({
    //contact details
    id: [],
    firstname: [, Validators.required],
    lastname: [, Validators.required],
    email: [],
    phone: [],
    address: [],
    zip: [],
    city: [],
    country: [],
    birthday: [],
    identification_number: [, Validators.required],
    identification_country: [],
    identification_created: [],
    identification_expire: [],
    afm: [],
    mobile: [],
    birth_place: [],
    //driver details
    notes: [],
    licence_number: [, Validators.required],
    licence_country: [],
    licence_created: [],
    licence_expire: [],
    role: [, Validators.required],
    contact_id: [],

    how_old: [],

    documents: [],

  });

  currentDay: Date = new Date();

  contactData: any = [];
  contactUser: any = [];
  rentals: any = [];
  bookings: any = [];
  quotes: any = [];
  // driverId!:any;
  invoices: any = [];
  customUrl!: string;

  constructor(protected injector: Injector,
    public urlSrv: Router, public loadSrv: MyLoadingService, public createRenSrv: CreateRentalModalService, private notificationSrv: NotificationService) {
    super(injector);
  }

  ngOnInit(): void {
    super.ngOnInit();
    console.log(this.contactUser?.id)

    this.form.controls.birthday.valueChanges.subscribe(data => {
      if (moment(data).diff(moment.now()) < 0) {
        this.form.controls.how_old.patchValue(moment(data).fromNow(true).substring(0, 3));
      } else {
        this.form.controls.how_old.patchValue(moment(new Date()).fromNow(true));
        if (data != '' && data != undefined && String(data).length < 5) {
          this.notificationSrv.showErrorNotification('μελλοντική ηλικία');
        }

      }
    });
  }


  fastDate1(inputDate: string) {
    if (inputDate.length == 6) {
      console.log('fastdate');
      let day = inputDate.substring(0, 2);
      if (day[0] == '0') {
        day = day.replace('0', '');
      }
      console.log('day ' + day);
      let month = inputDate.substring(2, 4);
      if (month[0] == '0') {
        month = month.replace('0', '');
      }
      console.log('month ' + month);
      let year = inputDate.substring(4, 6);
      console.log('year ' + year);
      if (+year > 10) {
        year = '19' + year;
      } else {
        year = '20' + year;
      }
      console.log('year final ' + year);
      console.log(new Date().setFullYear(+year, +month, +day));
      this.form.controls.birthday.patchValue(moment(new Date().setFullYear(+year, +month - 1, +day)).format('YYYY-MM-DD'));
    }

  }


  fastDate2(inputDate: string) {
    if (inputDate.length == 6) {
      console.log('fastdate');
      let day = inputDate.substring(0, 2);
      if (day[0] == '0') {
        day = day.replace('0', '');
      }
      console.log('day ' + day);
      let month = inputDate.substring(2, 4);
      if (month[0] == '0') {
        month = month.replace('0', '');
      }
      console.log('month ' + month);
      let year = inputDate.substring(4, 6);
      console.log('year ' + year);
      if (+year > 10) {
        year = '19' + year;
      } else {
        year = '20' + year;
      }
      console.log('year final ' + year);
      console.log(new Date().setFullYear(+year, +month, +day));
      this.form.controls.identification_created.patchValue(moment(new Date().setFullYear(+year, +month - 1, +day)).format('YYYY-MM-DD'));
    }
  }


  fastDate3(inputDate: string) {
    if (inputDate.length == 6) {
      console.log('fastdate');
      let day = inputDate.substring(0, 2);
      if (day[0] == '0') {
        day = day.replace('0', '');
      }
      console.log('day ' + day);
      let month = inputDate.substring(2, 4);
      if (month[0] == '0') {
        month = month.replace('0', '');
      }
      console.log('month ' + month);
      let year = inputDate.substring(4, 6);
      console.log('year ' + year);
      if (+year > 10) {
        year = '19' + year;
      } else {
        year = '20' + year;
      }
      console.log('year final ' + year);
      console.log(new Date().setFullYear(+year, +month, +day));
      this.form.controls.identification_expire.patchValue(moment(new Date().setFullYear(+year, +month - 1, +day)).format('YYYY-MM-DD'));
    }
  }


  fastDate4(inputDate: string) {
    if (inputDate.length == 6) {
      console.log('fastdate');
      let day = inputDate.substring(0, 2);
      if (day[0] == '0') {
        day = day.replace('0', '');
      }
      console.log('day ' + day);
      let month = inputDate.substring(2, 4);
      if (month[0] == '0') {
        month = month.replace('0', '');
      }
      console.log('month ' + month);
      let year = inputDate.substring(4, 6);
      console.log('year ' + year);
      if (+year > 10) {
        year = '19' + year;
      } else {
        year = '20' + year;
      }
      console.log('year final ' + year);
      console.log(new Date().setFullYear(+year, +month, +day));
      this.form.controls.licence_created.patchValue(moment(new Date().setFullYear(+year, +month - 1, +day)).format('YYYY-MM-DD'));
    }
  }


  fastDate5(inputDate: string) {
    if (inputDate.length == 6) {
      console.log('fastdate');
      let day = inputDate.substring(0, 2);
      if (day[0] == '0') {
        day = day.replace('0', '');
      }
      console.log('day ' + day);
      let month = inputDate.substring(2, 4);
      if (month[0] == '0') {
        month = month.replace('0', '');
      }
      console.log('month ' + month);
      let year = inputDate.substring(4, 6);
      console.log('year ' + year);
      if (+year > 10) {
        year = '19' + year;
      } else {
        year = '20' + year;
      }
      console.log('year final ' + year);
      console.log(new Date().setFullYear(+year, +month, +day));
      this.form.controls.licence_expire.patchValue(moment(new Date().setFullYear(+year, +month - 1, +day)).format('YYYY-MM-DD'));
    }
  }


}
