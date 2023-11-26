import { Component, Injector, ViewChild } from '@angular/core';
import { CreateFormComponent } from 'src/app/create-form/create-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { DocumentTypeFormComponent } from '../document-type-form/document-type-form.component';
import { IDocumentType } from '../document-type.interface';
import { DocumentTypeService } from '../document-type.service';

@Component({
  selector: 'app-create-document-type',
  templateUrl: './create-document-type.component.html',
  styleUrls: ['./create-document-type.component.scss'],
  providers: [{provide: ApiService, useClass: DocumentTypeService}]
})
export class CreateDocumentTypeComponent extends CreateFormComponent<IDocumentType> {
  @ViewChild(DocumentTypeFormComponent, {static: true}) formComponent!: DocumentTypeFormComponent;
}
