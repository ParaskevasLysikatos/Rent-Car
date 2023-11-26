import { Component, Inject, OnInit } from '@angular/core';
import { FormBuilder, FormControl, FormGroup } from '@angular/forms';
import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { Subscription } from 'rxjs/internal/Subscription';
import { ICancelReasons } from './cancel-reason.interface';
import { CancelReasonService } from './cancel-reason.service';


@Component({
  selector: 'app-cancel-reason',
  templateUrl: './cancel-reason.component.html',
  styleUrls: ['./cancel-reason.component.scss']
})
export class CancelReasonComponent implements OnInit {

  cancelSub: Subscription;
  cancels: ICancelReasons[] = [];

  myForm = new FormGroup({
    cancel_reason: new FormControl(''),
  });

  constructor(@Inject(MAT_DIALOG_DATA) public data: number, protected fb: FormBuilder,
    protected cancelSrv: CancelReasonService, public dialogRef: MatDialogRef<CancelReasonComponent>) {
  }


  ngOnInit() {
    this.cancelSrv.get().subscribe(res => { this.cancels = res['data'];
      this.myForm.controls.cancel_reason.patchValue(this.data);
      if (this.myForm.controls.cancel_reason.value == null || this.myForm.controls.cancel_reason.value == 0){
        this.myForm.controls.cancel_reason.patchValue(1);
      }
      this.cancelSrv.cancelSubject.next(+this.myForm.controls.cancel_reason.value);
      console.log(+this.myForm.controls.cancel_reason.value);
});
  }


  selectReason(event) {
    this.cancelSrv.cancelSubject.next(event);
    //console.log(event);
  }

  onClose(): void {
   // console.log(this.cancelSrv.cancelSubject.getValue());
    this.dialogRef.close();
  }

}
