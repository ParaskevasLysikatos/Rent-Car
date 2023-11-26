import { Component, Inject, OnInit, ViewChild } from '@angular/core';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { PrintRentalService } from './print-rental.service';
import { pdfDefaultOptions } from 'ngx-extended-pdf-viewer';
import { Print, PrintCheckboxService } from 'src/app/print-checkbox/print-checkbox.service';
import { delay, finalize, Subscription } from 'rxjs';
import { NotificationService } from 'src/app/_services/notification.service';
import { IRental } from '../rental.interface';
import { RentalService } from '../rental.service';
import { environment as env } from 'src/environments/environment';

import { COMMA, ENTER } from '@angular/cdk/keycodes';
import { MatChipInputEvent, MatChipList } from '@angular/material/chips';

@Component({
  selector: 'app-print-rental',
  templateUrl: './print-rental.component.html',
  styleUrls: ['./print-rental.component.scss']
})
export class PrintRentalComponent implements OnInit {
  myForm = new FormGroup({
    id: new FormControl(''),
    mail_to: new FormControl([]),
    mail_subject: new FormControl(''),
    mail_notes: new FormControl(''),
    pdf_src: new FormControl('')
  });

  addOnBlur = true;
  readonly separatorKeysCodes = [ENTER, COMMA] as const;
  emails: string[] = [];
  @ViewChild('chipList') chipList: MatChipList;
  enableSend: boolean = false;
  enableFrame = false;

  public page = 1;
  // public pageLabel!: string;
  printSub: Subscription;
  src: any;
  seq: string;
  envGlobal: string = `${env.apiUrl}`.replace('public/api', 'public');

  constructor(@Inject(MAT_DIALOG_DATA) public data: number,
    public dialogRef: MatDialogRef<PrintRentalComponent>,
    protected printRentalSrv: PrintRentalService,
     protected printCheckboxSrv: PrintCheckboxService,protected notificationSrv: NotificationService
     ,protected rentalSrv:RentalService<IRental>) { }

  ngOnInit() {
    this.printRentalSrv.get(this.data).subscribe(res => {
      this.rentalSrv.edit(String(this.data)).subscribe((res) =>
      {
       this.seq = res.sequence_number;
        this.myForm.controls.mail_subject.patchValue('BLUE RENT agreement – ' + res.sequence_number);
        this.emails.push(res.driver.contact.email);// needed cause is a chip list
        this.myForm.controls.mail_to.patchValue(res.driver.contact.email);
        this.myForm.controls.mail_notes.patchValue('The document BLUE RENT Rental Agreement ' + res.sequence_number + ' was signed by ' + res.driver.contact.lastname + ' ' + res.driver.contact.firstname + '.<br><br>The final document is attached.<br><br>For any further clarification please reply to this email.<br><br>Blue Rent a car.')
       });
      this.myForm.controls.id.setValue(this.data);
      this.src = this.envGlobal+'/storage/'+res.path; //new Blob([res], { type: 'application/pdf' });
      this.myForm.controls.pdf_src.setValue(res.path);
     // console.log(res.path);
     // console.log(this.myForm.value);
      this.enableFrame = true;
    });
  }

  onClose(): void {
    this.dialogRef.close();
    if (this.printCheckboxSrv.arrayPrints.length > 0) {
      this.printCheckboxSrv.showCurrentPrint.next(this.printCheckboxSrv.arrayPrints.pop());
      this.printSub = this.printCheckboxSrv.showCurrentPrint.pipe(delay(500)).subscribe((res: Print) => this.printCheckboxSrv.ShowPrint(res.component, res.data));
    }
    else {
      this.printCheckboxSrv.arrayPrints = [];
      this.printCheckboxSrv.showCurrentPrint.next(null);
    }
  }

  ngOnDestroy() {
   // this.printSub.unsubscribe();
  }

  sendMail(){
    this.myForm.markAllAsTouched();
    this.enableSend = true;
    this.printRentalSrv.mail(this.myForm.value).pipe(finalize(() => this.enableSend = false)).subscribe((res)=>this.notificationSrv.showSuccessNotification(res),(error)=>this.notificationSrv.showErrorNotification(error));
  }

  add(event: MatChipInputEvent): void {
    const value = (event.value || '').trim();
    if (value == '') {
      return;
    }
    //check if is email regex
    let regex = (new RegExp(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/));
    if (value.match(regex)) {
      // console.log('email pass');
    }
    else {
      this.notificationSrv.showErrorNotification("That was not a valid email, only valid email please");
      return;
      // console.log('email fails');
    }
    // Add our email
    if (value) {
      this.emails.push(value);
      this.emails = [...new Set(this.emails)];// cause blur event adds them again as event
      this.myForm.controls.mail_to.patchValue(this.emails);
    }
    // Clear the input value
    event.chipInput!.clear();
  }

  remove(email: string): void {
    const index = this.emails.indexOf(email);
    if (index >= 0) {
      this.emails.splice(index, 1);
      this.myForm.controls.mail_to.patchValue(this.emails);
    }
  }

}
