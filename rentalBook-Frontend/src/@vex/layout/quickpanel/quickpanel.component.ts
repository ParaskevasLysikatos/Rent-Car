import { Component, OnInit } from '@angular/core';
import { DateTime } from 'luxon';
import moment from 'moment';
import { Observable,interval } from 'rxjs';
import { RenBookmark, RentalTotalService } from 'src/app/rental/rental-total.service';
import { IUser } from 'src/app/user/user.interface';
import { AuthService } from 'src/app/_services/auth.service';
@Component({
  selector: 'vex-quickpanel',
  templateUrl: './quickpanel.component.html',
  styleUrls: ['./quickpanel.component.scss']
})
export class QuickpanelComponent implements OnInit {

  date!: string;
  dayName!: string;
  currentUser: IUser;
  currentDate = new Date();
  previousDate = moment().subtract(1, 'days');

  //Παραδόσεις - Checkout -booking
  stationBChOut!: string;// from user
  dateBChOut1!: string;// today start hour
  dateBChOut2!: string;// today end hour
  statusBChOut!: string;//pending

  //Σημερινές Μισθώσεις -rental
  stationRChOut!: string;// from user
  dateRChOut1!: string;// today start hour
  dateRChOut2!: string;// today end hour
  statusRChOut!: string;//active

  //Παραλαβές - Checkin -rental
  stationRChIn!: string;// from user
  dateRChIn1!: string;// today start hour
  dateRChIn2!: string;// today end hour
  statusRChIn!: string;//active

  //Εκρεμμείς Παραλαβές -rental
  dateRChIn2Pen!: string;// today end hour
  statusRChInPen!: string;//active

  constructor(public authSrv: AuthService,public renTotalSrv:RentalTotalService) {
    //real time datetime
    moment.locale('el');
    setInterval(() => {
      this.date = moment().local().format('LL');
      this.dayName = moment().local().format('LTS');
    }, 500);
  }

  ngOnInit() {
    this.authSrv.user.subscribe(res => this.currentUser=res);
    //Παραδόσεις - Checkout
    this.stationBChOut = this.currentUser?.station_id;
    this.dateBChOut1 = this.currentDate.toISOString().split('T')[0]+' 00:00:00';
    this.dateBChOut2 = this.currentDate.toISOString().split('T')[0]+' 23:59:59';
    this.statusBChOut = 'pending';
    //Σημερινές Μισθώσεις
    this.stationRChOut = this.currentUser?.station_id;
    this.dateRChOut1 = this.currentDate.toISOString().split('T')[0]+' 00:00:00';
    this.dateRChOut2 = this.currentDate.toISOString().split('T')[0]+' 23:59:59';
    this.statusRChOut = 'active';
    //Παραλαβές - Checkin
    this.stationRChIn = this.currentUser?.station_id;
    this.dateRChIn1 = this.currentDate.toISOString().split('T')[0]+' 00:00:00';
    this.dateRChIn2 = this.currentDate.toISOString().split('T')[0]+' 23:59:59';
    this.statusRChIn = 'active';
    //Εκρεμμείς Παραλαβές
    this.dateRChIn2Pen = this.previousDate.toISOString().split('T')[0]+' 23:59:59';
    this.statusRChInPen = 'active';
  }

  fillRenTotal(dateFromOut:string, dateToOut:string,dateFromIn:string, dateToIn:string){
    let bookmarkDates:RenBookmark={
      date_from_out:dateFromOut,
      date_to_out:dateToOut,
      date_from_in: dateFromIn,
      date_to_in: dateToIn,
    }
    this.renTotalSrv.bookmarkRental.next(bookmarkDates);
    console.log(this.renTotalSrv.bookmarkRental.getValue());
  }

}
