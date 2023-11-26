import { ComponentType } from '@angular/cdk/portal';
import { Component, ComponentFactoryResolver, ComponentRef, Inject, OnInit, ViewChild, ViewContainerRef } from '@angular/core';
import { FormGroup, ValidationErrors } from '@angular/forms';
import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { isObservable, Observable, Subscription } from 'rxjs';
import { ConfirmationDialogService } from '../confirmation-dialog/confirmation-dialog.service';
import { IDocuments } from '../documents/documents.interface';
import { DocumentsService } from '../documents/documents.service';

@Component({
  selector: 'app-form-dialog',
  templateUrl: './form-dialog.component.html',
  styleUrls: ['./form-dialog.component.scss']
})
export class FormDialogComponent implements OnInit {
  @ViewChild('formRef', {read: ViewContainerRef, static: true}) formRef!: ViewContainerRef;
  @ViewChild('dialog', {read: ViewContainerRef}) dialog!: ViewContainerRef;
  public component!: ComponentRef<any>;
  dialogRef!: MatDialogRef<any>;
  id!: string;

  uploadingDoc = false;
  uploadingDocSubscription: Subscription;


  constructor(@Inject(MAT_DIALOG_DATA) public data: any,
    public dialogSrv: ConfirmationDialogService, private docSrv: DocumentsService<IDocuments>) { }

  ngOnInit(): void {
    console.log(this.data.component.name);
    this.component = this.formRef.createComponent<ComponentType<any>>(this.data.component);
    for (const key of Object.keys(this.data?.componentData ?? {})) {
      this.component.instance[key] = this.data.componentData[key];
    }
    // this.component.instance.id = this.data.id;
    // this.component.instance.object = this.data.object;
    this.id = this.data?.componentData?.id ? this.data.componentData.id : this.data?.componentData.object?.id;

    this.docSrv.init();
    this.uploadingDocSubscription = this.docSrv.uploading$.subscribe(uploading => {
      this.uploadingDoc = uploading;
    });
 }

  closeDialog(data?: any): void {
    this.dialogRef.afterClosed().subscribe(res => {
      this.component.destroy();
    }).unsubscribe();
    this.dialogRef.close(data);
  }

  submit(): void {
    let submit: Observable<any> | boolean;
    if (this.component.instance.formComponent.form.valid) {// stop modal from closing when invalid
       submit = this.component.instance.submit();
    } else {
      this.component.instance.formComponent.form.markAllAsTouched();
      this.dialogSrv.showDialog('Υποχρεωτικά πεδία στη φόρμα', this.getFormValidationErrors(this.component.instance.formComponent.form));
    }
   // const submit: Observable<any>|boolean = this.component.instance.submit();
    if (isObservable(submit)) {
      if (this.data.ajax) {
        submit.subscribe(res => {
          this.closeDialog(res);
        });
      } else {
        this.closeDialog(this.component.instance.formComponent.form.value)
      }
    }
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




