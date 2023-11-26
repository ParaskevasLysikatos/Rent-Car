import { Component, Inject, OnInit, ViewChild } from '@angular/core';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { pdfDefaultOptions } from 'ngx-extended-pdf-viewer';
import { delay, finalize, Subscription } from 'rxjs';
import { Print, PrintCheckboxService } from 'src/app/print-checkbox/print-checkbox.service';
import { NotificationService } from 'src/app/_services/notification.service';
import { IInvoices } from '../invoices.interface';
import { InvoicesService } from '../invoices.service';
import { PrintInvoiceService } from './print-invoice.service';
import { environment as env } from 'src/environments/environment';

import { COMMA, ENTER } from '@angular/cdk/keycodes';
import { MatChipInputEvent, MatChipList } from '@angular/material/chips';

@Component({
  selector: 'app-invoice-print',
  templateUrl: './invoice-print.component.html',
  styleUrls: ['./invoice-print.component.scss']
})
export class InvoicePrintComponent implements OnInit {

  myForm = new FormGroup({
    id: new FormControl(''),
    mail_to: new FormControl([]),
    mail_subject: new FormControl(''),
    mail_notes: new FormControl(''),
    pdf_src: new FormControl('')
  });

  public page = 1;

  addOnBlur = true;
  readonly separatorKeysCodes = [ENTER, COMMA] as const;
  emails: string[] = [];
  @ViewChild('chipList') chipList: MatChipList;

  // public pageLabel!: string;
  printSub: Subscription;
  src: any;
  seq: string;
  envGlobal: string = `${env.apiUrl}`.replace('public/api', 'public');
  enableSend: boolean = false;
  enableFrame = false;

  constructor(@Inject(MAT_DIALOG_DATA) public data: number,
    public dialogRef: MatDialogRef<InvoicePrintComponent>, protected notificationSrv: NotificationService,
    protected printInvSrv: PrintInvoiceService, protected printCheckboxSrv: PrintCheckboxService, protected invoiceSrv: InvoicesService<IInvoices>) { }

  ngOnInit() {
    this.printInvSrv.get(this.data).subscribe(res => {
      this.invoiceSrv.edit(String(this.data)).subscribe((res) => {
        this.seq = res.sequence_number; this.myForm.controls.mail_subject.patchValue('BLUE RENT invoice – ' + res.sequence_number);
        this.emails.push(res.invoicee.email + res.invoicee.emailAgent);// needed cause is a chip list
        this.myForm.controls.mail_to.patchValue(res.invoicee.email + res.invoicee.emailAgent);
        this.myForm.controls.mail_notes.patchValue('The document BLUE RENT invoice ' + res.sequence_number + ' was signed by ' + res.invoicee.name + '.<br><br>The final document is attached.<br><br>For any further clarification please reply to this email.<br><br>Blue Rent a car.')
      });
      this.myForm.controls.id.setValue(this.data);
      this.src = this.envGlobal + '/storage/' + res.path; //new Blob([res], { type: 'application/pdf' });
    //  console.log(this.src);
      this.myForm.controls.pdf_src.setValue(res.path);
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

  sendMail() {
    this.myForm.markAllAsTouched();
    this.enableSend = true;
    this.printInvSrv.mail(this.myForm.value).pipe(finalize(() => this.enableSend = false)).subscribe((res) => this.notificationSrv.showSuccessNotification(res), (error) => this.notificationSrv.showErrorNotification(error));
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
