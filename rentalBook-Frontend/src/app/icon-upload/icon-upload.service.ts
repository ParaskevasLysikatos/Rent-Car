import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment as env } from 'src/environments/environment';
import { BehaviorSubject, tap, catchError, Observable } from 'rxjs';


@Injectable({
  providedIn: 'root'
})
export class IconUploadService {
  url = `${env.apiUrl}/`;
  filesUploading = 0;
  uploading: BehaviorSubject<boolean> = new BehaviorSubject(false);
  uploading$ = this.uploading.asObservable();

  constructor(private http: HttpClient) {

  }

  init() {
    this.filesUploading = 0;
    this.uploading.next(false);
  }

  uploadRemove(id: string, urlCustom: string) {
    return this.http.delete<any>(this.url + urlCustom + '/uploadRemove/'+ Number(id)).pipe(tap(() => {
    }), catchError((err: any) => {
      throw new (err);
    }));
  }



  uploadEdit(file: File,id: string,urlCustom: string) {
    this.filesUploading++;
    this.uploading.next(true);
    const form_data = new FormData();
    form_data.append('file', file, file.name);
    form_data.append('id', id);
    return this.http.post<any>(this.url+urlCustom+'/upload', form_data, { reportProgress: true }).pipe(tap(() => {
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
