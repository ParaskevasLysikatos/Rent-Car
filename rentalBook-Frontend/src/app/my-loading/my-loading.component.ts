import { Component, OnInit } from '@angular/core';
import { MyLoadingService } from './my-loading.service';

@Component({
  selector: 'app-my-loading',
  templateUrl: './my-loading.component.html',
  styleUrls: ['./my-loading.component.scss']
})
export class MyLoadingComponent implements OnInit {

  constructor(public loadingService: MyLoadingService) {

  }

  ngOnInit() {

  }

}
