import { Component, Injector, OnInit } from '@angular/core';
import { FormGroup, ValidationErrors } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { isObservable, Observable } from 'rxjs';
import { catchError, finalize, tap } from 'rxjs/operators';
import { PrintBookingService } from '../booking/print-booking/print-booking.service';
import { ConfirmationDialogService } from '../confirmation-dialog/confirmation-dialog.service';
import { FormDialogService } from '../form-dialog/form-dialog.service';
import { IconUploadService } from '../icon-upload/icon-upload.service';
import { PrintInvoiceService } from '../invoices/invoice-print/print-invoice.service';
import { IOptionsCollection } from '../options/options-collection.interface';
import { OptionsService } from '../options/options.service';
import { PrintQuoteService } from '../quotes/print-quote/print-quote.service';
import { PrintRentalService } from '../rental/print-rental/print-rental.service';
import { SingleFormService } from '../single-form/single-form.service';
import { IFormComponent } from '../_interfaces/form-component.interface';
import { ApiService } from '../_services/api-service.service';
import { NotificationService } from '../_services/notification.service';
import { HasIdType } from '../_types/has-id-type';

@Component({
  template: ''
})
export abstract class CreateFormComponent<Type extends HasIdType> implements OnInit {
  abstract formComponent: IFormComponent;
  route: ActivatedRoute;
  router: Router;
  formSrv: SingleFormService;
  dialogSrv: ConfirmationDialogService;
  apiSrv: ApiService<Type>;

  constructor(protected injector: Injector, private iconSrv: IconUploadService,protected formDialogSrv: FormDialogService,
    private optionsSrv: OptionsService<IOptionsCollection>,private printBookingSrv:PrintBookingService, private notificationSrv: NotificationService,
    private printQuoteSrv: PrintQuoteService, private printRentalSrv: PrintRentalService,private printInvSrv: PrintInvoiceService) {
    this.route = injector.get(ActivatedRoute);
    this.formSrv = injector.get(SingleFormService);
    this.dialogSrv = injector.get(ConfirmationDialogService);
    this.apiSrv = injector.get(ApiService);
    this.router = injector.get(Router);
  }

  ngOnInit(): void {
  }

  submit(): Observable<Type>|boolean {
    if (this.formComponent.form.invalid) {
      this.formComponent.form.markAllAsTouched();
      this.formSrv.waitSaveToComplete.next(false);
      return this.dialogSrv.showDialog('Υποχρεωτικά πεδία στη φόρμα', this.getFormValidationErrors(this.formComponent.form));
    }
    // if (this.formComponent.constructor.name == 'RentalFormComponent' || this.formComponent.constructor.name == 'BookingFormComponent' || this.formComponent.constructor.name == 'QuotesFormComponent') {// as options the values of item are needed for addOptionsFromRequest()
    //   this.formComponent.form.controls.options.patchValue(this.formComponent.form.get('summary_charges.items').value);
    //   console.log('options filled');
    // }
    const data: Type = this.formComponent.form.value;
   // console.log(this.apiSrv.url);
   //console.log(this.optionsSrv.url);
    if (this.apiSrv.url.includes('api/options')){
      this.apiSrv.url = this.optionsSrv.url;
    }
    return this.apiSrv.create(data).pipe(
      tap(res => {
        this.formSrv.saved(res.id);
        if(this.formComponent.form.contains('icon')){
          console.log('exei icon');
          this.iconSrv.uploadEdit(this.formComponent.form.controls.icon.value,res.id,this.formComponent.form.controls.customUrl.value).subscribe(res => {
          });
        }
        this.formComponent.form.markAsPristine();
        this.formComponent.form.markAsUntouched();
        //for first time created print
        //console.log(this.router.url);
        if (this.formComponent.constructor.name == 'RentalFormComponent' && this.formDialogSrv.comesFromModal.getValue()) {// when create in modal a comp , to not call auto-save function
          this.printRentalSrv.createFirstTime.next(true);
        }
        else if (this.formComponent.constructor.name == 'BookingFormComponent' && this.formDialogSrv.comesFromModal.getValue()){
          this.printBookingSrv.createFirstTime.next(true);
        }
        else if (this.formComponent.constructor.name == 'QuotesFormComponent' && this.formDialogSrv.comesFromModal.getValue()){
          this.printQuoteSrv.createFirstTime.next(true);
        }
        else if (this.formComponent.constructor.name == 'InvoicesFormComponent' && this.formDialogSrv.comesFromModal.getValue()) {
          this.printInvSrv.createFirstTime.next(true);
        }
      }),
      catchError(err => {
        this.notificationSrv.showErrorNotification(err.error);
        throw err;
      }), finalize(() => this.formSrv.waitSaveToComplete.next(false))
    );
  }

  submitted = (res) => {};

  onSubmit(): boolean {
    const submit = this.submit();
    if (isObservable(submit)) {
      submit.subscribe((res) => {
          this.submitted(res);
      });

      return true;
    }
    return false;
  }

  canDeactivate(): Observable<boolean> | Promise<boolean> | boolean {
    if (this.formComponent.form.dirty) {
      if (this.formComponent.form.untouched) { //to pass the check when from modal
        return true;
      }
      return this.dialogSrv.showDialog('Έχετε μη αποθηκευμένες αλλαγές. Είστε σίγουροι ότι θέλετε να φύγετε;');
    }
    return true;
  }


  getFormValidationErrors(form: FormGroup): any {

    const result = [];
    Object.keys(form.controls).forEach(key => {

      const controlErrors: ValidationErrors = form.get(key).errors;
      if (controlErrors) {
        Object.keys(controlErrors).forEach(keyError => {
          result.push({
            'control': key,
            'error': keyError,
            'value': controlErrors[keyError]
          });
        });
      }
    });

    return result;
  }
}
