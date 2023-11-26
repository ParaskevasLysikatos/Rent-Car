import { Component, HostListener, OnInit } from '@angular/core';
import { BehaviorSubject, map, ReplaySubject } from 'rxjs';
import { PopoverService } from 'src/@vex/components/popover/popover.service';
import { ToolbarComponent } from 'src/@vex/layout/toolbar/toolbar.component';
import { ConfigService } from 'src/@vex/services/config.service';
import { LayoutService } from 'src/@vex/services/layout.service';
import { NavigationService } from 'src/@vex/services/navigation.service';
import icLogout from '@iconify/icons-ic/twotone-logout';
import { AuthService } from '../_services/auth.service';
import emojioneGR from '@iconify/icons-emojione/flag-for-greece';
import { TooltipPosition } from '@angular/material/tooltip';
import { FormControl } from '@angular/forms';
import { IUser } from '../user/user.interface';
import icHome from '@iconify/icons-ic/twotone-home';


@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.scss']
})
export class HeaderComponent extends ToolbarComponent implements OnInit {
  title$ = this.configSrv.config$.pipe(map(config => config.sidenav.title));
  imageUrl$ = this.configSrv.config$.pipe(map(config => config.sidenav.imageUrl));
  sideNavOpened = true;
  icLogout = icLogout;
  emojioneGR = emojioneGR;


  icHome = icHome;
  currentUser = new BehaviorSubject<IUser>(null);

  positionOptions: TooltipPosition[] = ['right', 'left', 'above', 'below'];
  position = new FormControl(this.positionOptions[0]);
  position2 = new FormControl(this.positionOptions[1]);
  position3 = new FormControl(this.positionOptions[2]);
  position4 = new FormControl(this.positionOptions[3]);

  constructor(private layoutSrv: LayoutService,
    private configSrv: ConfigService,
    private navigationSrv: NavigationService,
    private popoverSrv: PopoverService,
    private authSrv: AuthService) {
      super(layoutSrv, configSrv, navigationSrv, popoverSrv);
    }

  ngOnInit(){
   // this.currentUser=JSON.parse(localStorage.getItem('loggedUser'));
    super.ngOnInit();
  }

  ngAfterContentChecked() {
    if(this.currentUser.getValue()==null){
      this.currentUser.next( JSON.parse(localStorage.getItem('loggedUser')));
    }
  }


  openSidenav() {
    if (this.mobileQuery) {
      this.layoutSrv.openSidenav();
    } else if (this.sideNavOpened) {
      this.sideNavOpened = false;
      this.layoutSrv.collapseSidenav();
    } else {
      this.sideNavOpened = true;
      this.layoutSrv.expandSidenav();
    }
  }

  logout() {
    this.authSrv.logout().subscribe();
  }

  @HostListener('body:keydown', ['$event'])
  keyboardEvent(event: KeyboardEvent) {
    if (['Enter', 'Escape', 'Tab', 'End', 'Home', 'ArrowLeft', 'ArrowRight', 'Delete'].indexOf(event.key) !== -1) {
      this.layoutSrv.closeSearch();
      this.layoutSrv.closeQuickpanel();
    }
  }

}
