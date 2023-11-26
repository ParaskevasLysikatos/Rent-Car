import { Component, Injector, ViewChild } from '@angular/core';
import { CreateFormComponent } from 'src/app/create-form/create-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { DocumentsFormComponent } from '../documents-form/documents-form.component';
import { IDocuments } from '../documents.interface';
import { DocumentsService } from '../documents.service';

@Component({
  selector: 'app-create-documents',
  templateUrl: './create-documents.component.html',
  styleUrls: ['./create-documents.component.scss'],
  providers: [{provide: ApiService, useClass: DocumentsService}]
})
export class CreateDocumentsComponent extends CreateFormComponent<IDocuments> {
  @ViewChild(DocumentsFormComponent, {static: true}) formComponent!: DocumentsFormComponent;
}
