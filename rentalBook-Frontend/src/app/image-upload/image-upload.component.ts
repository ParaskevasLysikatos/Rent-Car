import { Component, forwardRef, Input, OnInit } from '@angular/core';
import { Subscription } from 'rxjs';
import { ImageUploadService } from './image-upload.service';
import { IImage } from './image.interface';
import { ControlValueAccessor, NG_VALUE_ACCESSOR } from '@angular/forms';

@Component({
  selector: 'app-image-upload',
  templateUrl: './image-upload.component.html',
  styleUrls: ['./image-upload.component.scss'],
  providers: [
    {
      provide: NG_VALUE_ACCESSOR,
      useExisting: forwardRef(() => ImageUploadComponent),
      multi: true
    }
  ]
})
export class ImageUploadComponent implements OnInit {

  @Input() multiple = false;
  files: IImage[] = [];
  _value: any;
  uploadsSubscription: Subscription[] = [];

  constructor(private imgSrv: ImageUploadService) { }

  ngOnInit(): void {
    if (this.multiple) {
      this._value = [];
    }
  }

  ngOnDestroy() {
    this.uploadsSubscription.forEach(subscription => {
      subscription.unsubscribe();
    })
  }

  // On file Select
  onChange: any = () => { };

  // OnClick of button Upload
  onUpload(event) {
    const files: any = event.target.files;
    for (const file of files) {
      this.uploadsSubscription.push(this.imgSrv.upload(file).subscribe(res => {
        this.value = res;
        this.onChange(this.value);
      }));
    }
  }

  // Function to call when the input is touched (when a star is clicked).
  onTouched = () => { };

  set value(file: any) {//value is to send the files ids on save
    if (this.multiple) {
      if (Array.isArray(file)) {
        this._value = file.map(file => file.id);
        this.files = [...file];
      } else {
        this.files.push(file);
        this._value.push(file.id);
      }
    } else {
      this._value = file.id;
      this.files = [{ ...file }];
    }
  }

  get value() {
    return this._value;
  }

  remove(idx: number) {
    this.files.splice(idx, 1);
    this.value = this.files;
    this.onChange(this.value)
  }

  // Allows Angular to update the model (rating).
  // Update the model and changes needed for the view here.
  writeValue(document: IImage[] | IImage): void {
    if (document) {
      this.value = document;
      this.onChange(this.value);
    }
  }

  // Allows Angular to register a function to call when the model (rating) changes.
  // Save the function as a property to call later here.
  registerOnChange(fn: (rating: number) => void): void {
    this.onChange = fn;
  }


  // Allows Angular to register a function to call when the input has been touched.
  // Save the function as a property to call later here.
  registerOnTouched(fn: () => void): void {
    this.onTouched = fn;
  }


}
