import { Injectable, Injector } from '@angular/core';
import { BehaviorSubject, catchError, of, tap, throwError } from 'rxjs';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { IDocuments } from './documents.interface';

@Injectable({
  providedIn: 'root'
})
export class DocumentsService<T extends IDocuments> extends ApiService<T> {
  url = `${env.apiUrl}/document`;

  total_DocSub: BehaviorSubject<IDocuments> = new BehaviorSubject(null);
  filesUploading = 0;
  uploading: BehaviorSubject<boolean> = new BehaviorSubject(false);
  uploading$ = this.uploading.asObservable();

  constructor(protected injector: Injector) {
    super(injector);
  }

  init() {
    this.filesUploading = 0;
    this.uploading.next(false);
  }

  upload(file: File) {
    this.filesUploading++;
    this.uploading.next(true);
    const form_data=new FormData();
    form_data.append('file', file, file.name);
    return this.http.post<IDocuments>(this.url+'/upload', form_data, {reportProgress: true}).pipe(tap(()=>{
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
