import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Router } from '@angular/router';
import { th } from 'date-fns/locale';
import { BehaviorSubject, interval, Observable, ReplaySubject, Subject } from 'rxjs';
import { catchError, delay, last, retry, take, tap } from 'rxjs/operators';
import { environment as env } from 'src/environments/environment';
import { IUser } from '../user/user.interface';
import { IAuth } from '../_interfaces/auth.interface';
import { NotificationService } from './notification.service';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  url = `${env.apiUrl}`;
  per_page = 15;
  user = new ReplaySubject<IUser>(1);


  constructor(private http: HttpClient, private router: Router, private notificationSrv: NotificationService) {
    this.per_page = Number(localStorage.getItem('per_page') ?? 0);
    setInterval(() => {
      if(this.isAuth()){
        this.refresh();
        this.setPerPage(this.getPerPage());
      }
    }, 1000 * 60 *30);//30 min sec *60 = 1 min *30=half hour
  }

  login(email: string, password: string): Observable<IAuth> | Observable<any> {
    return this.http.post<IAuth>(this.url + '/login', { email, password }).pipe(
      tap(res => {
        localStorage.setItem('access_token', res.access_token);
        this.getUser();
        localStorage.setItem('per_page', '15');
      }), catchError((err: any) => {
        console.log(err);
        this.notificationSrv.showErrorNotification(err.message);
        throw err;
      }),
    )
  };

  removeToken() {
    localStorage.removeItem('access_token');
    this.router.navigate(['/login']);
  }

  logout() {
    return this.http.post<any>(this.url + '/logout', {})
      .pipe(
        tap(res => {
          this.removeToken();
          this.user.next(null);
          localStorage.removeItem('loggedUser');
          localStorage.removeItem('per_page');
        })
      );
  }

  errorHandler(error: any): void {
    console.log(error);
  }

  getToken() {
    return localStorage.getItem('access_token');
  }


  refresh() {
    this.http.get<any>(this.url + '/refresh').subscribe(((res) => { localStorage.removeItem('access_token'); ; localStorage.setItem('access_token', res.access_token); }));
  }

  setPerPage(per_page: number) {
    this.per_page = per_page;
    localStorage.setItem('per_page', this.per_page.toString());
  }

  getPerPage(): number {
    return this.per_page;
  }

  getUser() {
    this.http.get<IUser>(this.url + '/user').subscribe(((res: IUser) => { this.user.next(res); localStorage.setItem('loggedUser', JSON.stringify(res)); }));
  }

  private isTokenExpired(token: string) {
    const expiry = (JSON.parse(atob(token.split('.')[1]))).exp;
    return (Math.floor((new Date).getTime() / 1000)) >= expiry;
  }

  isAuth() {
    const token = this.getToken();
    return token && !this.isTokenExpired(token) ? true : false;
  }


  resetPassword(email: string): Observable<string> {
    return this.http.post<string>(this.url + '/resetPassword', { email });
  }

}
