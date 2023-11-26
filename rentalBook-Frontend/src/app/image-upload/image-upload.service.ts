
import { HttpClient } from '@angular/common/http';
import { Injectable, Injector, Input } from '@angular/core';
import { BehaviorSubject, catchError, of, tap, throwError } from 'rxjs';
import { environment as env } from 'src/environments/environment';
import { IImage } from './image.interface';

@Injectable({
  providedIn: 'root'
})
export class ImageUploadService {
  url = `${env.apiUrl}/image`;
  filesUploading = 0;
  uploading: BehaviorSubject<boolean> = new BehaviorSubject(false);
  uploading$ = this.uploading.asObservable();
  
  constructor(private http:HttpClient) {

  }

  init() {
    this.filesUploading = 0;
    this.uploading.next(false);
  }

  upload(file: File) {
    this.filesUploading++;
    this.uploading.next(true);
    const form_data = new FormData();
    form_data.append('file', file, file.name);
    return this.http.post<IImage>(this.url + '/upload', form_data, { reportProgress: true }).pipe(tap(() => {
      this.fileUploaded();
    }), catchError((err: any) => {
      this.fileUploaded();
      throw new (err);
    }));
  }

  fileUploaded() {
    this.filesUploading--;
    if (this.filesUploading === 0) {
      this.uploading.next(false);
    }
  }
}
