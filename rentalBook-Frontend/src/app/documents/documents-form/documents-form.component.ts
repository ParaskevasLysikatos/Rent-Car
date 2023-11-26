import { Component, ElementRef, Injector, OnInit, ViewChild } from '@angular/core';
import { Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { AbstractFormComponent } from 'src/app/abstract-form/abstract-form.component';
import { IDocumentType } from 'src/app/document-type/document-type.interface';
import { DocumentTypeService } from 'src/app/document-type/document-type.service';
import { IconUploadService } from 'src/app/icon-upload/icon-upload.service';


@Component({
  selector: 'app-documents-form',
  templateUrl: './documents-form.component.html',
  styleUrls: ['./documents-form.component.scss']
})
export class DocumentsFormComponent extends AbstractFormComponent implements OnInit {
  form = this.fb.group({
    id: null,
    type_id :[],
    user_id : [],
    path : [],
    mime_type :[],
    md5 : [],
    comments:[],
    name : [],
    document_type : [],
    documents: [],


  });

  docTypes: any[] = [];




  constructor(protected injector: Injector, protected docTypesSrv: DocumentTypeService<IDocumentType>, private iconSrv: IconUploadService, private route: ActivatedRoute, private urlSrv: Router) {
    super(injector);

  }

  ngOnInit() {
    super.ngOnInit();
    this.docTypesSrv.get({}, undefined, -1).subscribe(res => { this.docTypes = res.data })

  }


}
