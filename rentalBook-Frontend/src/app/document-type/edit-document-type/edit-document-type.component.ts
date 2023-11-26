import { Component, Injector, ViewChild } from '@angular/core';
import { EditFormComponent } from 'src/app/edit-form/edit-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { DocumentTypeFormComponent } from '../document-type-form/document-type-form.component';
import { IDocumentType } from '../document-type.interface';
import { DocumentTypeService } from '../document-type.service';


@Component({
  selector: 'app-edit-document-type',
  templateUrl: './edit-document-type.component.html',
  styleUrls: ['./edit-document-type.component.scss'],
  providers: [{provide: ApiService, useClass: DocumentTypeService}]
})
export class EditDocumentTypeComponent extends EditFormComponent<IDocumentType> {
  @ViewChild(DocumentTypeFormComponent, {static: true}) formComponent!: DocumentTypeFormComponent;
}
