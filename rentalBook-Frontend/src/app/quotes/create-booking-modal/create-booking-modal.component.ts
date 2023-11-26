import { Component, Inject, OnInit } from '@angular/core';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import { Router } from '@angular/router';
import { delay, finalize, tap } from 'rxjs';
import { IBookingItem } from 'src/app/booking-item/booking-item.interface';
import { ISummaryCharges } from 'src/app/summary-charges/summary-charges.interface';
import { CreateBookingModalService } from './create-booking-modal.service';

@Component({
  selector: 'app-create-booking-modal',
  templateUrl: './create-booking-modal.component.html',
  styleUrls: ['./create-booking-modal.component.scss']
})
export class CreateBookingModalComponent implements OnInit {

  constructor(@Inject(MAT_DIALOG_DATA) public data: any,
    protected createBookSrv: CreateBookingModalService, public dialogRef: MatDialogRef<CreateBookingModalComponent>
    , protected router: Router) {
  }


  ngOnInit() {
    if (!this.data?.form.touched) {
      this.createBookSrv.createBookSubject.next(this.data?.main);
      this.createBookSrv.stationOutSub.next(this.data?.station_out);
      this.createBookSrv.stationInSub.next(this.data?.station_in);
      this.createBookSrv.driverSub.next(this.data?.driver_id);
      //this.createBookSrv.vehicleSub.next(this.data?.vehicle);
      this.createBookSrv.sourceSub.next(this.data?.source_id);
      this.createBookSrv.agentSub.next(this.data?.agent_id);
      this.createBookSrv.typeSub.next(this.data?.type_id);
      this.createBookSrv.payersSub.next(this.data?.payers);
      this.createBookSrv.summaryChargesSub.next(this.data?.summaryCharges);//summary will be initialized in rental init additem
      this.createBookSrv.summaryChargesItemsSub.next(this.nullifyOptionsId(this.data?.summaryChargesItems));// summary items
      this.router.navigate(['/bookings/create']);
      this.dialogRef.close();
    }

  }



  onClose(): void {
    this.dialogRef.close();
  }


  noSave() {
    this.createBookSrv.createBookSubject.next(this.data?.main);
    this.createBookSrv.stationOutSub.next(this.data?.station_out);
    this.createBookSrv.stationInSub.next(this.data?.station_in);
    this.createBookSrv.driverSub.next(this.data?.driver_id);
    //this.createBookSrv.vehicleSub.next(this.data?.vehicle);
    this.createBookSrv.sourceSub.next(this.data?.source_id);
    this.createBookSrv.agentSub.next(this.data?.agent_id);
    this.createBookSrv.typeSub.next(this.data?.type_id);
    this.createBookSrv.payersSub.next(this.data?.payers);
    this.createBookSrv.summaryChargesSub.next(this.data?.summaryCharges);//summary will be initialized in rental init additem
    this.createBookSrv.summaryChargesItemsSub.next(this.nullifyOptionsId(this.data?.summaryChargesItems));// summary items
    this.router.navigate(['/bookings/create']);
    this.dialogRef.close();
  }

  withSave() {
    this.createBookSrv.callSaveSubject.next(true);
    this.dialogRef.close();
    this.dialogRef.afterClosed().pipe(delay(3000), tap(() => this.router.navigate(['/bookings/create']))).subscribe(() => {
      this.createBookSrv.createBookSubject.next(this.data?.main);
      this.createBookSrv.stationOutSub.next(this.data?.station_out);
      this.createBookSrv.stationInSub.next(this.data?.station_in);
      this.createBookSrv.driverSub.next(this.data?.driver_id);
      //this.createBookSrv.vehicleSub.next(this.data?.vehicle);
      this.createBookSrv.sourceSub.next(this.data?.source_id);
      this.createBookSrv.agentSub.next(this.data?.agent_id);
      this.createBookSrv.typeSub.next(this.data?.type_id);
      this.createBookSrv.payersSub.next(this.data?.payers);
      this.createBookSrv.summaryChargesSub.next(this.data?.summaryCharges);//summary will be initialized in rental init additem
      this.createBookSrv.summaryChargesItemsSub.next(this.nullifyOptionsId(this.data?.summaryChargesItems));// summary items
    });

  }

  sorting(array: IBookingItem[]): IBookingItem[] {
    return array.sort((a, b) => a.option.order - b.option.order || +a.option.id - +b.option.id);
  }

  nullifyOptionsId(array: IBookingItem[]): IBookingItem[] {
    array.forEach((item) => { item.id = ''; item.option_id = item.option.id; });
    return array;
  }

  orderSummaryCharges(summary: ISummaryCharges): ISummaryCharges {
    let summaryItemsSorted: IBookingItem[] = this.sorting(summary.items);
    summary.items = summaryItemsSorted;
    return summary;
  }

}
