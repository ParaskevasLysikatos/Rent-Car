import { Component, Input, OnInit } from '@angular/core';
import { BreadcrumbsComponent as VexBreadcrumbsComponent } from 'src/@vex/components/breadcrumbs/breadcrumbs.component';
import { trackByValue } from 'src/@vex/utils/track-by';
import { ICrumb } from './crumb.interface';
import icHome from '@iconify/icons-ic/twotone-home';
import { BreadcrumbService } from '../_services/breadcrub.service';
import { Observable, Subscription } from 'rxjs';
import { AuthService } from '../_services/auth.service';
import { IUser } from '../user/user.interface';
import { BreadcrumbsService } from './breadcrumbs.service'; //for headers

@Component({
  selector: 'app-breadcrumbs',
  templateUrl: './breadcrumbs.component.html',
  styleUrls: ['./breadcrumbs.component.scss']
})
export class BreadcrumbsComponent implements OnInit {
  trackByValue = trackByValue;
  icHome = icHome;
  crumbs$: Observable<ICrumb[]>;
  currentUser!: IUser;

  constructor(private readonly breadcrumbSrv: BreadcrumbService,protected authSrv: AuthService,
  public breadcrumbsSrv: BreadcrumbsService) {//for headers
    this.crumbs$ = breadcrumbSrv.breadcrumbs$;


  }

  ngOnInit() {
   // this.userSubscription = this.authSrv.user.subscribe(res => { this.currentUser = res; });
   // this.currentUser=JSON.parse(localStorage.getItem('loggedUser'));
  }


}
