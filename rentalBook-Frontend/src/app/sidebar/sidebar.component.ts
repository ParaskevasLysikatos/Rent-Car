import { Component } from '@angular/core';
import { SidenavComponent } from 'src/@vex/layout/sidenav/sidenav.component';
import { ConfigService } from 'src/@vex/services/config.service';
import { LayoutService } from 'src/@vex/services/layout.service';
import { NavigationService } from 'src/@vex/services/navigation.service';
import { trackByRoute } from 'src/@vex/utils/track-by';

@Component({
  selector: 'app-sidebar',
  templateUrl: './sidebar.component.html',
  styleUrls: ['./sidebar.component.scss']
})

export class SidebarComponent extends SidenavComponent {
  constructor(private navigationSrv: NavigationService, private layoutSrv: LayoutService, private configSrv: ConfigService) {
    super(navigationSrv, layoutSrv, configSrv);
  }
}
