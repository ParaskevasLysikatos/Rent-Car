import { Component, ElementRef, EventEmitter, forwardRef, OnDestroy, OnInit, Output, ViewChild } from '@angular/core';
import { ControlValueAccessor, FormControl, NG_VALUE_ACCESSOR } from '@angular/forms';
import { AbstractSelectorComponent } from '../abstract-selector/abstract-selector.component';
import { COMMA, ENTER } from '@angular/cdk/keycodes';
import { MatChipInputEvent } from '@angular/material/chips';
import { MatAutocompleteSelectedEvent } from '@angular/material/autocomplete';
import { TagService } from 'src/app/tag/tag.service';
import { Tag } from 'src/app/tag/tag.interface';
import { debounceTime, filter, last, take, takeUntil, tap } from 'rxjs/operators';
import { Subject } from 'rxjs';


@Component({
  selector: 'app-tag-selector',
  templateUrl: './tag-selector.component.html',
  styleUrls: ['./tag-selector.component.scss'],
  providers: [
    {
      provide: NG_VALUE_ACCESSOR,
      useExisting: forwardRef(() => TagSelectorComponent),
      multi: true
    },
    {
      provide: AbstractSelectorComponent,
      useExisting: TagSelectorComponent
    }
  ]
})
export class TagSelectorComponent implements OnInit, OnDestroy, ControlValueAccessor {
  tagCtrl = new FormControl();
  separatorKeysCodes = [ENTER, COMMA];
  tags: Tag[] = [];
  @Output() tagsOutput: EventEmitter<Tag[] | []> = new EventEmitter();
  tagOptions: Tag[] = [];
  searching = false;
  protected _onDestroy = new Subject<void>();

  onChange: any = () => {};

  constructor(private tagSrv: TagService) { }

  ngOnInit(): void {
    this.tagCtrl.valueChanges
      .pipe(
        filter(search => !!search),
        tap(() => { this.searching = true; }),
        takeUntil(this._onDestroy),
        debounceTime(500),
        takeUntil(this._onDestroy)
      )
      .subscribe(input => {
        this.tagSrv.get({ search: input },undefined, -1).subscribe(res => {
          this.tagOptions = [];//clear
          this.tagOptions = res.data.filter((tag, index) => tag.title.includes(input));
          if(this.tagOptions.length==0){
            console.log(this.tags);
            this.tagOptions = res.data;
          }
          this.searching = false;
          this.uniqueTags();
        });
    });

  }

  ngOnDestroy(): void {
    this._onDestroy.next();
    this._onDestroy.complete();
  }

  add(event: MatChipInputEvent): void {
    const input = event.chipInput?.inputElement;
    const value = event.value;

    // Add our fruit
    if ((value || '').trim()) {
      const tag = {
        title: value.trim()
      };
      this.tags.push(tag);
      this.tagsOutput.emit(this.tags);
    }

    // Reset the input value
    if (input) {
      input.value = '';
    }

    this.tagCtrl.patchValue(null);
    this.uniqueTags();
  }

  remove(fruit: any): void {
    const index = this.tags.indexOf(fruit);

    if (index >= 0) {
      this.tags.splice(index, 1);
      this.tagsOutput.emit(this.tags);
    }
    this.uniqueTags();
  }

  selected(event: MatAutocompleteSelectedEvent, input: HTMLInputElement): void {
    const tag = {
      title: event.option.viewValue
    };
    this.tags.push(tag);
    input.value = '';
    this.tagCtrl.patchValue(null);
    this.tagsOutput.emit(this.tags);
    this.uniqueTags();
  }

  writeValue(value: any): void {
   // this.uniqueTags();
  }

  registerOnChange(fn: any): void {
    this.onChange = fn;
  }

  registerOnTouched(): void {

  }


  uniqueTags(){
    var ArrTemp = [];
    this.tags.filter(function (item) {
      var i = ArrTemp.findIndex(x => (x.title == item.title));
      if (i <= -1) {
        ArrTemp.push(item);
      }
      return null;
    });
    this.tags = ArrTemp;
    //this.tagOptions = ArrTemp;

    this.tagOptions = this.methodIntersectionOut(this.tagOptions,this.tags);
    console.log(this.tagOptions);
  }

  methodIntersectionOut(arr1:Tag[], arr2:Tag[]){
    let res = [];
    res = arr1.filter(el => {
      return !arr2.find(element => {
        return element.title === el.title;
      });
    });
    return res;
  }

}
