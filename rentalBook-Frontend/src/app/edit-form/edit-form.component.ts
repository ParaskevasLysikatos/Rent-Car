import { AfterViewInit, Component, ElementRef, Injector, Input, OnDestroy, OnInit, ViewChild, ViewContainerRef } from '@angular/core';
import { FormGroup, ValidationErrors } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { isObservable, Observable, Subscription } from 'rxjs';
import { catchError, finalize, tap } from 'rxjs/operators';
import { IBookingItem } from '../booking-item/booking-item.interface';
import { CreateRentalModalService } from '../booking/create-rental-modal/create-rental-modal.service';
import { ConfirmationDialogService } from '../confirmation-dialog/confirmation-dialog.service';
import { PrintInvoiceService } from '../invoices/invoice-print/print-invoice.service';
import { CreateBookingModalService } from '../quotes/create-booking-modal/create-booking-modal.service';
import { SingleFormService } from '../single-form/single-form.service';
import { ISummaryCharges } from '../summary-charges/summary-charges.interface';
import { IFormComponent } from '../_interfaces/form-component.interface';
import { ApiService } from '../_services/api-service.service';
import { NotificationService } from '../_services/notification.service';
import { SpinnerService } from '../_services/spinner.service';

@Component({
  template: ''
})
export abstract class EditFormComponent<Type extends {[key: string]: any}> implements OnInit, AfterViewInit, OnDestroy {
  abstract formComponent: IFormComponent;
  id!: string;
  onDelete$!: Subscription;
  @Input() object!: Type;
  protected route: ActivatedRoute;
  protected router: Router;
  protected formSrv: SingleFormService;
  protected dialogSrv: ConfirmationDialogService;
  protected spinnerSrv: SpinnerService;
  protected elementRef: ElementRef;
  protected viewContainerRef: ViewContainerRef;
  protected apiSrv: ApiService<Type>;
  protected notificationSrv: NotificationService;
  private printInvSrv: PrintInvoiceService;


  constructor(protected injector: Injector) {
    this.route = injector.get(ActivatedRoute);
    this.router = injector.get(Router);
    this.formSrv = injector.get(SingleFormService);
    this.dialogSrv = injector.get(ConfirmationDialogService);
    this.spinnerSrv = injector.get(SpinnerService);
    this.elementRef = injector.get(ElementRef);
    this.viewContainerRef = injector.get(ViewContainerRef);
    this.apiSrv = injector.get(ApiService);
    this.notificationSrv = injector.get(NotificationService);
    this.printInvSrv = injector.get(PrintInvoiceService);
  }

  ngOnInit(): void {
    this.onDelete$ = this.formSrv.onDelete().subscribe(() => {
      this.apiSrv.delete(this.id).subscribe(res => {
        this.router.navigate(['../'], {relativeTo: this.route});
      });
    });
    this.spinnerSrv.showSpinner(this.elementRef);
    if (!this.object) {
      this.id = this.id ? this.id : this.route.snapshot.paramMap.get('id') ?? '';
      this.apiSrv.edit(this.id)
        .subscribe(
          (res) => {
            //this.spinnerSrv.dataLoad();
            this.object = res;
            res = this.normalizeResponse(res);
            if (this.formComponent) {
              this.formComponent.form.patchValue(res);
             if (this.formComponent.constructor.name == 'RentalFormComponent' || this.formComponent.constructor.name == 'BookingFormComponent' || this.formComponent.constructor.name == 'QuotesFormComponent') {
               this.formComponent.form.controls.summary_charges.patchValue(this.orderSummaryCharges(res.summary_charges));
               console.log(this.orderSummaryCharges(res.summary_charges));
              }else{
               this.formComponent.form.markAsPristine();
               this.formComponent.form.markAsUntouched();
              // this.spinnerSrv.dataLoad();
              }
            }
            this.afterDataLoad(res);
            this.spinnerSrv.dataLoad();
          },
          (err) => {
            (this.notificationSrv.showErrorNotification(err.error));
            this.spinnerSrv.hideSpinner();
          });
    } else {
        this.spinnerSrv.dataLoad();
        this.formComponent.form.patchValue(this.object);
        this.formComponent.form.markAsPristine();
      this.formComponent.form.markAsUntouched();
       //this.spinnerSrv.dataLoad();
    }
  }

  ngAfterViewInit(): void {

  }

  ngOnDestroy(): void {
    this.spinnerSrv.hideSpinner();
   // this.onDelete$.unsubscribe();
  }

  afterDataLoad(data: Type): void {}

  normalizeResponse(res: any) {
    return res;
  }

  submit(): Observable<Type>|boolean {
    if (this.formComponent.form.invalid) {
      this.formComponent.form.markAllAsTouched();
      this.formSrv.waitSaveToComplete.next(false);
      return this.dialogSrv.showDialog('Υποχρεωτικά πεδία στη φόρμα',this.getFormValidationErrors(this.formComponent.form));
    }
    this.spinnerSrv.showSpinner(this.elementRef);
    const data: Type = this.formComponent.form.value;
   // const data: Type = objData;
   // console.log(data);
    return this.apiSrv.update(this.id, data).pipe(
      tap((res: Type) => {
        //this.spinnerSrv.dataLoad();
        this.object = res;
        res = this.normalizeResponse(res);
        this.formComponent.form.patchValue(res);
        this.formComponent.form.markAsPristine();
       // this.spinnerSrv.dataLoad();
        this.afterDataLoad(res);
        this.spinnerSrv.dataLoad();
        this.formSrv.saved(this.id);
        this.formComponent.form.markAsPristine();
        this.notificationSrv.showSuccessNotification('Επιτυχής αποθήκευση');

         if (this.formComponent.constructor.name == 'InvoicesFormComponent') {
           console.log('inv print');
          this.printInvSrv.createFirstTime.next(true);
        }
        // this.notificationSrv.showSuccessNotification('Επιτυχής αποθήκευση');
      }),
      catchError(err => {
        this.notificationSrv.showErrorNotification(err.error);
        this.spinnerSrv.hideSpinner();
        throw err;
      }), finalize(() => { this.formSrv.waitSaveToComplete.next(false), this.formComponent.form.markAsPristine(); this.formComponent.form.markAsUntouched(); })
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
      if(this.formComponent.form.untouched){ //to pass the check when from modal
        return true;
      }
      return this.dialogSrv.showDialog('Έχετε μη αποθηκευμένες αλλαγές. Είστε σίγουροι ότι θέλετε να φύγετε;');
    }
    return true;
  }


  getFormValidationErrors(form: FormGroup) :any {

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


  sorting(array: IBookingItem[]): IBookingItem[] {
    return array.sort((a, b) => a.option.order - b.option.order || +a.option.id - +b.option.id);
  }

  orderSummaryCharges(summary:ISummaryCharges):ISummaryCharges{
    let summaryItemsSorted: IBookingItem[] = this.sorting(summary.items);
    summary.items = summaryItemsSorted;
    return summary;
  }

}


// let sourceCpl = this.formComponent.sourceComplete;
// let driverCpl = this.formComponent.driverComplete;
// let companyCpl = this.formComponent.companyComplete;
// let stationOutCpl = this.formComponent.stationOutComplete;
// let stationInCpl = this.formComponent.stationInComplete;
// let agentCpl = this.formComponent.agentComplete;
// let groupCpl = this.formComponent.groupComplete;
// let optionsCpl = this.formComponent.optionsItemsComplete;
