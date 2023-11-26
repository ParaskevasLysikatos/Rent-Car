import { Component, ElementRef, Injector, OnInit, ViewChild } from '@angular/core';
import { FormArray, FormControl, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { ICategories } from 'src/app/categories/categories.interface';
import { CategoriesService } from 'src/app/categories/categories.service';
import { ICharacteristics } from 'src/app/characteristics/characteristics.interface';
import { CharacteristicsService } from 'src/app/characteristics/characteristics.service';
import { CombinedService } from 'src/app/home/combined.service';
import { IconUploadService } from 'src/app/icon-upload/icon-upload.service';
import { MultilingualFormComponent } from 'src/app/multilingual-form/multilingual-form.component';
import { IOptions } from 'src/app/options/options.interface';
import { OptionsService } from 'src/app/options/options.service';


@Component({
  selector: 'app-types-form',
  templateUrl: './types-form.component.html',
  styleUrls: ['./types-form.component.scss']
})
export class TypesFormComponent extends MultilingualFormComponent implements OnInit {
  form = this.fb.group({
    id:[],
    slug: [],
    icon: [],
    category_id: [],
    min_category: [],
    max_category: [],
    excess: [],
    international_code: [],
    options: [],
    characteristics: [],
    images:[],
    customUrl:[],//for icon upload
  });

  iconData!: string;
  file: File = null; // Variable to store file
  category_Max_Min: any = [];
  characteristicsData: ICharacteristics[] = [];
  characteristicsFormArray = new FormArray([]);
  optionsData: IOptions[] = [];
  optionsFormArray = new FormArray([]);
  checkUrl: boolean = true;
  customUrl: string;
  clickedDelIcon:boolean = true;
  @ViewChild('myInput')
  myInput: ElementRef;

  constructor(protected injector: Injector, protected categorySrv: CategoriesService<ICategories>,
    protected optionsSrv: OptionsService<IOptions>, protected characteristicsSrv: CharacteristicsService<ICharacteristics>,
    private iconSrv: IconUploadService, private route: ActivatedRoute, private urlSrv: Router, public combinedSrv: CombinedService) {
    super(injector);

  }

  ngOnInit(): void {
    super.ngOnInit();
    if (this.route.snapshot.routeConfig?.path == 'create') {
      this.checkUrl = false;
    }
    this.customUrl = this.urlSrv.url.split('/')[1];
    this.form.controls.customUrl.patchValue(this.customUrl);

    this.combinedSrv.getTypes().subscribe((res) => {
      this.category_Max_Min = res.categories;

      this.characteristicsData = res.characteristics;
      this.characteristicsData.forEach(() => this.characteristicsFormArray.push(new FormControl(false)));
      if (this.form.controls.characteristics.value) { // to ensure that first will come options data and then patch their values
        this.setUpSelectedCharacteristics();
      }

      this.optionsData = res.options.filter((item) => item.option_type == 'extras');
      this.optionsData.forEach(() => this.optionsFormArray.push(new FormControl(false)));
      if (this.form.controls.options.value) { // to ensure that first will come options data and then patch their values
        this.setUpSelectedOptions();
      }

    });

    //this.categorySrv.get({}, undefined, -1).subscribe(res => { this.category_Max_Min = res.data });

    this.optionsSrv.setType('extras');

    // this.optionsSrv.get({}, undefined, -1).subscribe(res => {
    //   this.optionsData = res.data;
    //   res.data.forEach(() => this.optionsFormArray.push(new FormControl(false)));
    //   if (this.form.controls.options.value) { // to ensure that first will come options data and then patch their values
    //     this.setUpSelectedOptions();
    //   }
    // });

    // this.characteristicsSrv.get({}, undefined, -1).subscribe(res => {
    //   this.characteristicsData = res.data;
    //   res.data.forEach(() => this.characteristicsFormArray.push(new FormControl(false)));
    //   if (this.form.controls.characteristics.value) { // to ensure that first will come options data and then patch their values
    //     this.setUpSelectedCharacteristics();
    //   }
    // });

    this.form.controls.options.valueChanges.subscribe(value => {
      if (!value) {
        this.optionsData.forEach((value, index) => {
          this.optionsFormArray.controls[index]?.patchValue(false);
        });
      }
    });


    this.form.controls.characteristics.valueChanges.subscribe(value => {
      if (!value) {
        this.characteristicsData.forEach((value, index) => {
          this.characteristicsFormArray.controls[index]?.patchValue(false);
        });
      }
    });
  }


  changeOptions() {
    const optionsIds: string[] = [];
    for (let i = 0; i < this.optionsFormArray.length; i++) {
      if (this.optionsFormArray.controls[i].value == true) {
        optionsIds.push(this.optionsData[i].id);
      }

    }
    this.form.controls.options.patchValue(optionsIds);
    this.form.markAsDirty();
  }


  changeCharacteristics() {
    const characteristicsIds: string[] = [];
    for (let i = 0; i < this.characteristicsFormArray.length; i++) {
      if (this.characteristicsFormArray.controls[i].value == true) {
        characteristicsIds.push(this.characteristicsData[i].id);
      }
    }
    this.form.controls.characteristics.patchValue(characteristicsIds, {});
    this.form.markAsDirty();
  }


  setUpSelectedOptions(): void {
    this.optionsData.forEach((value, index) => {
      if (this.form.get('options')?.value.indexOf(value.id) != -1) {    //-1 means not found
        this.optionsFormArray.controls[index]?.patchValue(true);
      }
    });
  }


  setUpSelectedCharacteristics(): void {
    this.characteristicsData.forEach((value, index) => {
      if (this.form.get('characteristics')?.value?.indexOf(value.id) != -1) {    //-1 means not found
        this.characteristicsFormArray.controls[index]?.patchValue(true);
      }
    });
  }


  get options() {
    return this.optionsFormArray as FormArray;
  }

  get optionControls() {
    return this.optionsFormArray.controls as FormControl[];
  }



  get characteristics() {
    return this.characteristicsFormArray as FormArray;
  }

  get characteristicControls() {
    return this.characteristicsFormArray.controls as FormControl[];
  }


  removeIcon(){
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





}
