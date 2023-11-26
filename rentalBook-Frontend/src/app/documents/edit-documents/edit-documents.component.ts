import { Component, Injector, ViewChild } from '@angular/core';
import { EditFormComponent } from 'src/app/edit-form/edit-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { DocumentsFormComponent } from '../documents-form/documents-form.component';
import { IDocuments } from '../documents.interface';
import { DocumentsService } from '../documents.service';

@Component({
  selector: 'app-edit-documents',
  templateUrl: './edit-documents.component.html',
  styleUrls: ['./edit-documents.component.scss'],
  providers: [{provide: ApiService, useClass: DocumentsService}]
})
export class EditDocumentsComponent extends EditFormComponent<IDocuments> {
  @ViewChild(DocumentsFormComponent, {static: true}) formComponent!: DocumentsFormComponent;

  afterDataLoad(res:IDocuments) {
   // this.formComponent.iconData = res.public_path;
  }



}
