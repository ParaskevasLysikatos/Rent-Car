import { HttpErrorResponse, HttpEvent, HttpHandler, HttpHeaders, HttpInterceptor, HttpRequest, HttpResponse } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, throwError } from 'rxjs';
import { catchError, delay, map } from 'rxjs/operators';
import { AuthService } from './auth.service';
import { NotificationService } from './notification.service';

@Injectable()
export class HttpInterceptorService implements HttpInterceptor {

  constructor(private authService: AuthService, private notificationSrv: NotificationService) { }

  intercept(request: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
    const token = this.authService.getToken();
    if (token){
      request = request.clone({
        headers: new HttpHeaders({
          Authorization: 'Bearer ' + token,
          Accept: 'application/json',
          "Access-Control-Allow-Origin": "*",
          "Access-Control-Allow-Methods": "DELETE, PATCH, POST, GET, OPTIONS",
          "Access-Control-Allow-Headers": "Content-Type, Authorization, X-Requested-With"
        })
      });
    }
    return next.handle(request).pipe(delay(100),
      map(event => {
        if (event instanceof HttpResponse) {
            event = event.clone({ body: event.body });
        }
        return event;
      }),
      catchError((error: HttpErrorResponse) => {
        if (error.error instanceof ErrorEvent) {
          // client-side error or network error

        } else {
          if (error.status === 400) {
            console.log(error);
            // show error bad request
            // if (error.error) { this.notificationSrv.showErrorNotification(error.error); }
            // else{
            //   this.notificationSrv.showErrorNotification(error.message);
            // }
           //edit-create files will show the notifications
          }
          // TODO: Clean up following by introducing method
        else  if (error.status === 498) {
            // TODO: Destroy local session; redirect to /login
          }
         else if (error.status === 401) {
            this.authService.removeToken();
          }
        else  if (error.status === 429) {
            this.notificationSrv.showErrorNotification('πολλά request, timeout έγινε από server');
          }

        // else if (error.status === 500) {
        //     if (typeof error.message != 'object') {
        //       this.notificationSrv.showErrorNotification(error?.message);
        //     }
        //     else if (typeof error.error != 'object') {
        //       this.notificationSrv.showErrorNotification(error?.error);
        //     }
        //   }


        }
        throw error;
      })
    );
  }
}
