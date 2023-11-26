
import { Component, ElementRef, Injector, OnInit, ViewChild } from '@angular/core';
import { Validators } from '@angular/forms';
import { ActivatedRoute, Router} from '@angular/router';
import { IconUploadService } from 'src/app/icon-upload/icon-upload.service';
import { MultilingualFormComponent } from 'src/app/multilingual-form/multilingual-form.component';
import { IOptionsCollection } from '../options-collection.interface';
import { OptionsService } from '../options.service';

@Component({
  selector: 'app-options-form',
  templateUrl: './options-form.component.html',
  styleUrls: ['./options-form.component.scss']
})
export class OptionsFormComponent extends MultilingualFormComponent implements OnInit {
  form = this.fb.group({
    id: [],
    cost_max: [],
  //  cost: [], not used
    code: [],
    cost_daily: [],
    items_max: [],
    icon: [],
    default_on: [],
    order: [],
    active_daily_cost: [],
    customUrl: [],//for icon upload
  });
  protected apiSrv!: OptionsService<IOptionsCollection>;
  type: string;//url for save

numbers:number[]=[1,2,3,4,5,6,7,8,9,10];
//my:any=this.form.value.items_max;
  iconData!: string;
  checkUrl: boolean = true;
  file: File = null; // Variable to store file
  customUrl: string;
  clickedDelIcon:boolean= true;
  @ViewChild('myInput')
  myInput: ElementRef;

boolActiveCost: boolean=false;
  constructor(protected injector: Injector, private iconSrv: IconUploadService, private route: ActivatedRoute, private urlSrv: Router,private optionsSrv: OptionsService<IOptionsCollection>) {
    super(injector);
  }

ngOnInit(): void {
    super.ngOnInit();
  if (this.route.snapshot.routeConfig?.path == 'create') {
    this.checkUrl = false;
  }
 //console.log(this.route.snapshot.params);
 // console.log(this.urlSrv.url.split('/')[1]);
  this.customUrl = this.urlSrv.url.split('/')[1] + '/' + this.urlSrv.url.split('/')[2];
 // console.log(this.customUrl);
  this.form.controls.customUrl.patchValue(this.customUrl);

  this.type = this.urlSrv.url.split('/')[2];
  console.log(this.type);
  this.optionsSrv.setType(this.type);
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
      this.iconSrv.uploadEdit(this.file, this.form.controls.id.value,this.customUrl).subscribe(res => {
        this.iconData = res.icon;
        this.clickedDelIcon = true;
        this.myInput.nativeElement.value = "";
      });
    }
  }


  ngOnDestroy() {
    this.optionsSrv.setType(null);
  }

}
