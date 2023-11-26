import { Component, ElementRef, Injector, OnInit, ViewChild } from '@angular/core';
import { Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { IconUploadService } from 'src/app/icon-upload/icon-upload.service';
import { MultilingualFormComponent } from 'src/app/multilingual-form/multilingual-form.component';

@Component({
  selector: 'app-characteristics-form',
  templateUrl: './characteristics-form.component.html',
  styleUrls: ['./characteristics-form.component.scss']
})
export class CharacteristicsFormComponent extends MultilingualFormComponent implements OnInit {
  form = this.fb.group({
    id:[],
    icon: [],
    customUrl: [],//for icon upload
  });

  iconData!: string;
  file: File = null; // Variable to store file
  checkUrl: boolean = true;
  customUrl: string;
  clickedDelIcon: boolean = true;
  @ViewChild('myInput')
  myInput: ElementRef;

  constructor(protected injector: Injector, private iconSrv: IconUploadService, private route: ActivatedRoute, private urlSrv: Router) {
    super(injector);
  }

  ngOnInit() {
    super.ngOnInit();
    if (this.route.snapshot.routeConfig?.path == 'create') {
      this.checkUrl = false;
    }
    this.customUrl = this.urlSrv.url.split('/')[1];
    this.form.controls.customUrl.patchValue(this.customUrl);
  }

  removeIcon() {
    this.iconSrv.uploadRemove(this.form.controls.id.value, this.customUrl).subscribe(res => {
      this.clickedDelIcon = false;
    });
  }


  onChange(event) {
    // this.file = event.target.files[0];
    //  console.log(this.form.controls.id.value);
    // console.log(this.form.controls.icon.value);
    this.file = (event.target as HTMLInputElement).files[0];
    this.form.patchValue({
      'icon': this.file
    });
    this.form.get('icon').updateValueAndValidity()
    //console.log(this.form.controls.icon.value);

    if (this.checkUrl) {
      this.iconSrv.uploadEdit(this.file, this.form.controls.id.value, this.customUrl).subscribe(res => {
        this.iconData = res.icon;
        this.clickedDelIcon = true;
        this.myInput.nativeElement.value = "";
      });
    }
  }

}
