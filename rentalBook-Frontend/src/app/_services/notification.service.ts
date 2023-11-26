import { Injectable } from '@angular/core';
import { MatSnackBar } from '@angular/material/snack-bar';
import { NotificationComponent } from '../notification/notification.component';

@Injectable({
  providedIn: 'root'
})
export class NotificationService {

  constructor(private snackbar: MatSnackBar) { }

  showSuccessNotification(message: string) {
    this.showNotification(message, 'bg-primary');
  }

  showErrorNotification(message: string) {
    this.showNotification(message, 'bg-warn');
  }

  showNotification(message: string, panelClass: string) {
    this.snackbar.openFromComponent(NotificationComponent, {
      data: {
        message
      },
      panelClass,
      duration: 5000
    });
  }
}
