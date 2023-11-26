import { Component, ElementRef, Injector, OnInit, ViewChild } from '@angular/core';
import { FormArray, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { Observable, observable } from 'rxjs';
import { IconUploadService } from 'src/app/icon-upload/icon-upload.service';
import { MultilingualFormComponent } from 'src/app/multilingual-form/multilingual-form.component';

@Component({
  selector: 'app-brand-form',
  templateUrl: './brand-form.component.html',
  styleUrls: ['./brand-form.component.scss']
})
export class BrandFormComponent extends MultilingualFormComponent implements OnInit {
  form = this.fb.group({
    id: null,
    slug: [],
    icon: [],
    customUrl: [],//for icon upload
    forms:this.fb.array([]),
})

checkUrl: boolean = false;

checkUrlForIcon:boolean =true;
  iconData!: string;
  file: File = null; // Variable to store file
  customUrl: string;
  clickedDelIcon: boolean = true;
  @ViewChild('myInput')
  myInput: ElementRef;

  printing_forms: any = [];
  constructor(protected injector: Injector, private route: ActivatedRoute, private iconSrv: IconUploadService, private urlSrv: Router) {
    super(injector);
  }


 ngOnInit(){
    super.ngOnInit();
    console.log(this.route.snapshot.routeConfig?.path);
     if(this.route.snapshot.routeConfig?.path=='create'){
      this.checkUrl = true;
       this.checkUrlForIcon = false;

    }
   this.customUrl = this.urlSrv.url.split('/')[1];
   this.form.controls.customUrl.patchValue(this.customUrl);
      //console.log(this.form.controls.forms)
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

    if (this.checkUrlForIcon) {
      this.iconSrv.uploadEdit(this.file, this.form.controls.id.value, this.customUrl).subscribe(res => {
        this.iconData = res.icon;
        this.clickedDelIcon = true;
        this.myInput.nativeElement.value = "";
      });
    }
  }




  forms(): FormArray {
  return this.form.get("forms") as FormArray
}





  newForm(form_name:string,placeholder_text_color: string,
  primary_background_color:string,primary_text_color:string,
  secondary_background_color:string,secondary_text_color:string): FormGroup {
  return this.fb.group({
    form_name:form_name,
    placeholder_text_color:placeholder_text_color,
    primary_background_color:primary_background_color,
    primary_text_color:primary_text_color,
    secondary_background_color:secondary_background_color,
    secondary_text_color:secondary_text_color
  })
}

  addForm(form_name:string,placeholder_text_color: string,
  primary_background_color:string,primary_text_color:string,
  secondary_background_color:string,secondary_text_color:string) {
  this.forms().push(this.newForm(form_name,placeholder_text_color,
  primary_background_color,primary_text_color,
  secondary_background_color,secondary_text_color));
}

}
