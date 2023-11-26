import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { BehaviorSubject, tap } from 'rxjs';
import { environment as env } from 'src/environments/environment';

@Injectable({
  providedIn: 'root'
})
export class DocumentUploadService {
  filesUploading = 0;
  url: string = `${env.apiUrl}/documents`;
  constructor(private http:HttpClient) { }

uploading:BehaviorSubject<boolean>=new BehaviorSubject(false)
uploading$ = this.uploading.asObservable();

  init() {
    this.filesUploading = 0;
    this.uploading.next(false);
  }


create(file:File){
  this.filesUploading++;
  const form_data=new FormData();
  form_data.append('file', file);
  return this.http.post(this.url+'/upload', form_data, {reportProgress: true}).pipe(tap(()=>{
    this.fileUploaded();
  }));
}

  fileUploaded() {
    this.filesUploading--;
    if (this.filesUploading === 0) {
      this.uploading.next(false);
    }
  }

}
