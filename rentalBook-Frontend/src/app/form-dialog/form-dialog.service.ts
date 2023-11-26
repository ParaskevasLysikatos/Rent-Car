import { ComponentType } from '@angular/cdk/portal';
import { ComponentRef, Injectable } from '@angular/core';
import { MatDialog, MatDialogRef } from '@angular/material/dialog';
import { BehaviorSubject, delay, finalize, Observable, Subscription, tap } from 'rxjs';
import { IDocuments } from '../documents/documents.interface';
import { DocumentsService } from '../documents/documents.service';
import { FormDialogComponent } from './form-dialog.component';

@Injectable({
  providedIn: 'root'
})
export class FormDialogService {
  public dialogRef!: MatDialogRef<any>;
  comesFromModal: BehaviorSubject<boolean> = new BehaviorSubject(true);



  constructor(private dialog: MatDialog, ) {}

  showDialog(component: ComponentType<any>, componentData: any = {}, ajax = true) {
    this.comesFromModal.next(false);
    this.dialogRef = this.dialog.open(FormDialogComponent, {
      disableClose: true,
      autoFocus: false,
      width: '95%',
      height: '90%',
      position: {top: '1.75rem'},
      data: {
        component,
        componentData,
        ajax
      }
    });

    this.dialogRef.componentInstance.dialogRef = this.dialogRef;
    this.dialogRef.afterOpened().pipe(tap(res => this.comesFromModal.next(false)));


    return this.dialogRef.afterClosed().pipe(tap(()=> this.comesFromModal.next(true)));//prevent auto save in preview
  }
}
