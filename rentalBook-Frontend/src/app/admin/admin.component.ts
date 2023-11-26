import { CdkOverlayOrigin } from '@angular/cdk/overlay';
import { AfterViewInit, Component, OnInit, TemplateRef, ViewChild } from '@angular/core';
import { Subscription } from 'rxjs';
import { AuthService } from '../_services/auth.service';

@Component({
  selector: 'app-admin',
  templateUrl: './admin.component.html',
  styleUrls: ['./admin.component.scss']
})
export class AdminComponent implements OnInit, AfterViewInit {
  opened = true;
  menuSubscription!: Subscription;
  toolbar!: TemplateRef<any>|null;

  constructor(private authSrv: AuthService) { }

  ngOnInit(): void {
  }

  ngAfterViewInit(): void {
    // console.log(this.toolbar);
  }

  logout() {
    this.authSrv.logout().subscribe();
  }
}
