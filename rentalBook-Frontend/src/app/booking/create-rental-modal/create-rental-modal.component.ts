import { Component, Inject, OnInit } from '@angular/core';
import { AbstractControl} from '@angular/forms';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import { Router } from '@angular/router';
import { delay, tap } from 'rxjs';
import { IBookingItem } from 'src/app/booking-item/booking-item.interface';
import { ISummaryCharges } from 'src/app/summary-charges/summary-charges.interface';
import { CreateRentalModalService } from './create-rental-modal.service';

@Component({
  selector: 'app-create-rental-modal',
  templateUrl: './create-rental-modal.component.html',
  styleUrls: ['./create-rental-modal.component.scss']
})
export class CreateRentalModalComponent implements OnInit {

  constructor(@Inject(MAT_DIALOG_DATA) public data: any,
    protected createRenSrv: CreateRentalModalService, public dialogRef: MatDialogRef<CreateRentalModalComponent>
    ,protected router: Router) {
  }


  ngOnInit() {
    if (!this.data?.form.touched){
      this.createRenSrv.createRenSubject.next(this.data?.main);
      this.createRenSrv.stationOutSub.next(this.data?.station_out);
      this.createRenSrv.stationInSub.next(this.data?.station_in);
      this.createRenSrv.driverSub.next(this.data?.driver);
      this.createRenSrv.vehicleSub.next(this.data?.vehicle);
      this.createRenSrv.sourceSub.next(this.data?.source_id);
      this.createRenSrv.agentSub.next(this.data?.agent_id);
      this.createRenSrv.typeSub.next(this.data?.type_id);
      this.createRenSrv.payersSub.next(this.data?.payers);
      this.createRenSrv.summaryChargesSub.next(this.data?.summaryCharges);//summary will be initialized in rental init additem
      this.createRenSrv.summaryChargesItemsSub.next(this.nullifyOptionsId(this.data?.summaryChargesItems));// summary items
      this.router.navigate(['/rentals/create']);
      this.dialogRef.close();
    }
  }



  onClose(): void {
    this.dialogRef.close();
  }


  noSave(){
    this.createRenSrv.createRenSubject.next(this.data?.main);
    this.createRenSrv.stationOutSub.next(this.data?.station_out);
    this.createRenSrv.stationInSub.next(this.data?.station_in);
    this.createRenSrv.driverSub.next(this.data?.driver);
    this.createRenSrv.vehicleSub.next(this.data?.vehicle);
    this.createRenSrv.sourceSub.next(this.data?.source_id);
    this.createRenSrv.agentSub.next(this.data?.agent_id);
    this.createRenSrv.typeSub.next(this.data?.type_id);
    this.createRenSrv.payersSub.next(this.data?.payers);
    this.createRenSrv.summaryChargesSub.next(this.data?.summaryCharges);//summary will be initialized in rental init additem
    this.createRenSrv.summaryChargesItemsSub.next(this.nullifyOptionsId(this.data?.summaryChargesItems));// summary items
    this.router.navigate(['/rentals/create']);
    this.dialogRef.close();
  }

  withSave(){
    this.createRenSrv.callSaveSubject.next(true);
    this.dialogRef.close();
    this.dialogRef.afterClosed().pipe(delay(3000), tap(() => this.router.navigate(['/rentals/create']))).subscribe(() => {
      this.createRenSrv.createRenSubject.next(this.data?.main);
      this.createRenSrv.stationOutSub.next(this.data?.station_out);
      this.createRenSrv.stationInSub.next(this.data?.station_in);
      this.createRenSrv.driverSub.next(this.data?.driver);
      this.createRenSrv.vehicleSub.next(this.data?.vehicle);
      this.createRenSrv.sourceSub.next(this.data?.source_id);
      this.createRenSrv.agentSub.next(this.data?.agent_id);
      this.createRenSrv.typeSub.next(this.data?.type_id);
      this.createRenSrv.payersSub.next(this.data?.payers);
      this.createRenSrv.summaryChargesSub.next(this.data?.summaryCharges);//summary will be initialized in rental init additem
      this.createRenSrv.summaryChargesItemsSub.next(this.nullifyOptionsId(this.data?.summaryChargesItems));// summary items
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
