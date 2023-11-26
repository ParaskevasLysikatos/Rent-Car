import { Component, OnInit } from '@angular/core';
import { fadeInUp400ms } from 'src/@vex/animations/fade-in-up.animation';
import { LayoutComponent } from 'src/@vex/layout/layout.component';

@Component({
  selector: 'app-page-layout',
  templateUrl: './page-layout.component.html',
  styleUrls: ['./page-layout.component.scss'],
  animations: [
    fadeInUp400ms
  ]
})
export class PageLayoutComponent extends LayoutComponent implements OnInit {
  title
}
