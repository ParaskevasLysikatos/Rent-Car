import { Injectable } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { BehaviorSubject, ReplaySubject, Subject } from 'rxjs';

export class Print {
  constructor(public component: any, public data: any) { }
}

@Injectable({
  providedIn: 'root'
})
export class PrintCheckboxService {

  showCurrentPrint = new ReplaySubject <{component: any, data: any}>(1);
  arrayPrints: Print[] = [];

  constructor(protected matDialog: MatDialog) { }

  ShowPrint(component: any, data: any) {
    let dialog = this.matDialog.open(component, {
      width: '100%',
      height: '100%',
      data: data,
      id: data?.sequence_number,
      autoFocus: false,
      disableClose: true
    });
    // console.log(dialog.id);
  }

}
