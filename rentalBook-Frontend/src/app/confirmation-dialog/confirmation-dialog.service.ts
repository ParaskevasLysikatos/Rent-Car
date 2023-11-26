import { Injectable } from '@angular/core';
import { FormGroup } from '@angular/forms';
import { MatDialog, MatDialogRef } from '@angular/material/dialog';
import { Observable } from 'rxjs';
import { ConfirmationDialogComponent } from './confirmation-dialog.component';

@Injectable({
  providedIn: 'root'
})
export class ConfirmationDialogService {
  private dialogRef!: MatDialogRef<ConfirmationDialogComponent>;

  constructor(private dialog: MatDialog) {}

  showDialog(message: string,form?: FormGroup,title = 'Επιβεβαίωση'): Observable<any> {
    this.dialogRef = this.dialog.open(ConfirmationDialogComponent, {
      disableClose: false
    });
    this.dialogRef.componentInstance.confirmTitle = title;
    this.dialogRef.componentInstance.confirmMessage = message;
    this.dialogRef.componentInstance.confirmErrors = form;

    return this.dialogRef.afterClosed();
  }
}
