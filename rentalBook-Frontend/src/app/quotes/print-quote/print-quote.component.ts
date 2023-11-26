import { Component, HostListener, Inject, OnInit, ViewChild } from '@angular/core';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import { Subscription, delay, finalize } from 'rxjs';
import { PrintCheckboxService, Print } from 'src/app/print-checkbox/print-checkbox.service';
import { PrintRentalComponent } from 'src/app/rental/print-rental/print-rental.component';
import { NotificationService } from 'src/app/_services/notification.service';
import { IQuotes } from '../quotes.interface';
import { QuotesService } from '../quotes.service';
import { PrintQuoteService } from './print-quote.service';
import { environment as env } from 'src/environments/environment';

import { COMMA, ENTER } from '@angular/cdk/keycodes';
import { MatChipInputEvent, MatChipList } from '@angular/material/chips';

@Component({
  selector: 'app-print-quote',
  templateUrl: './print-quote.component.html',
  styleUrls: ['./print-quote.component.scss']
})
export class PrintQuoteComponent implements OnInit {

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

  public page = 1;
  // public pageLabel!: string;
  src: any;
  seq: string;
  envGlobal: string = `${env.apiUrl}`.replace('public/api', 'public');
  enableSend: boolean = false;
  enableFrame = false;

  constructor(@Inject(MAT_DIALOG_DATA) public data: number,
    public dialogRef: MatDialogRef<PrintRentalComponent>,
    protected printQuoteSrv: PrintQuoteService,
    protected printCheckboxSrv: PrintCheckboxService, protected notificationSrv: NotificationService,
    protected quoteSrv: QuotesService<IQuotes>) { }

  ngOnInit() {
    this.printQuoteSrv.get(this.data).subscribe(res => {
      this.quoteSrv.edit(String(this.data)).subscribe((res) => {
        this.seq = res.sequence_number;
        this.myForm.controls.mail_subject.patchValue('BLUE RENT agreement – ' + res.sequence_number);
        if (res.customer) {
          this.emails.push(res.customer.contact.email);// needed cause is a chip list
          this.myForm.controls.mail_to.patchValue(res.customer.contact.email);
          this.myForm.controls.mail_notes.patchValue('The document BLUE RENT Quote Agreement ' + res.sequence_number + ' was signed by ' + res.customer.contact.lastname + ' ' + res.customer.contact.firstname + '.<br><br>The final document is attached.<br><br>For any further clarification please reply to this email.<br><br>Blue Rent a car.')
        }
      });
      this.myForm.controls.id.setValue(this.data);
      this.src = this.envGlobal + '/storage/' + res.path; //new Blob([res], { type: 'application/pdf' });
      this.myForm.controls.pdf_src.setValue(res.path);
      //console.log(this.src);
      this.enableFrame = true;
    });
  }

  onClose(): void {
    this.dialogRef.close();
    if (this.printCheckboxSrv.arrayPrints.length > 0) {
      this.printCheckboxSrv.showCurrentPrint.next(this.printCheckboxSrv.arrayPrints.pop());
      this.printCheckboxSrv.showCurrentPrint.pipe(delay(500)).subscribe((res: Print) => this.printCheckboxSrv.ShowPrint(res.component, res.data));
    }
    else{
      this.printCheckboxSrv.arrayPrints = [];
      this.printCheckboxSrv.showCurrentPrint.next(null);
    }
  }

  // @HostListener('window:beforeunload') //<-- Do NOT put a semicolon here
  // async destroy() {
  //   await this.ngOnDestroy()
  // }

  // ngOnDestroy() {
  //   this.data = null;
  // }

  sendMail() {
    this.myForm.markAllAsTouched();
    this.enableSend = true;
    this.printQuoteSrv.mail(this.myForm.value).pipe(finalize(() => this.enableSend = false)).subscribe((res) => this.notificationSrv.showSuccessNotification(res), (error) => this.notificationSrv.showErrorNotification(error));
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
