import { I } from '@angular/cdk/keycodes';
import { Component, OnInit } from '@angular/core';
import { FormBuilder, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { map } from 'rxjs';
import { fadeInUp400ms } from 'src/@vex/animations/fade-in-up.animation';
import { ConfigService } from 'src/@vex/services/config.service';
import { AuthService } from '../_services/auth.service';
import { NotificationService } from '../_services/notification.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss'],
  animations: [
    fadeInUp400ms
  ]
})
export class LoginComponent implements OnInit {
  title$ = this.configSrv.config$.pipe(map(config => config.sidenav.title));
  imageUrl$ = this.configSrv.config$.pipe(map(config => config.sidenav.imageUrl));
  hide = true;
  loginForm = this.fb.group({
    username: [null, Validators.required],
    password: [null, Validators.required],
    rememberMe: []
  });
  returnUrl='/home';
  show:boolean = false;

  constructor(private fb: FormBuilder, private authSrv: AuthService, private router: Router, private configSrv: ConfigService,
    private route: ActivatedRoute, public notifySrv:NotificationService) {
    if (localStorage.getItem('rememberMe') != 'true')
    {
       localStorage.setItem('rememberMe', this.loginForm.controls.rememberMe.value);
    }
    else{
      this.loginForm.controls.rememberMe.patchValue(true);
    }
    }

  ngOnInit(): void {
    let access = localStorage.getItem('access_token');
    let signedUser = localStorage.getItem('loggedUser');
    let rememberMe = localStorage.getItem('rememberMe');
    if (access!=null && signedUser!=null){
     if( this.authSrv.isAuth() || (rememberMe!='null' && signedUser!=null)){
       this.router.navigate([this.returnUrl]);
       this.authSrv.getUser();
     }
    }

    this.returnUrl = this.route.snapshot.queryParams.returnUrl;
    this.returnUrl = this.returnUrl ? this.returnUrl : '/home';
  }

  onSubmit() {
    localStorage.setItem('rememberMe', this.loginForm.controls.rememberMe.value);
    this.authSrv.login(this.loginForm.get('username')?.value, this.loginForm.get('password')?.value)
      .subscribe(res => {
        console.log(this.loginForm.value);
        this.router.navigate([this.returnUrl]);
        this.authSrv.getUser();
      });
  }


  showSend(){
    if(this.show){
      this.show = false;
    }else{
      this.show=true;
    }
  }

  sendPassword(){
    this.authSrv.resetPassword(this.loginForm.controls.username.value).subscribe(res => {
      this.notifySrv.showSuccessNotification(res);
    }, err => { this.notifySrv.showErrorNotification(err); });
  }

}





