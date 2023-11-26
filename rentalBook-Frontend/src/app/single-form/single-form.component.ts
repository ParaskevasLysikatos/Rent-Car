import { Component, OnInit, Output, EventEmitter, Input, AfterViewInit, ViewChild, TemplateRef, Injector, HostListener } from '@angular/core';
import { FormGroup } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { BehaviorSubject, finalize, Subscription, tap } from 'rxjs';
import { CreateRentalModalService } from '../booking/create-rental-modal/create-rental-modal.service';
import { CompanyPreferencesService } from '../company_preferences/company.service';
import { ConfirmationDialogService } from '../confirmation-dialog/confirmation-dialog.service';
import { IDocuments } from '../documents/documents.interface';
import { DocumentsService } from '../documents/documents.service';
import { FormDialogService } from '../form-dialog/form-dialog.service';
import { IconUploadService } from '../icon-upload/icon-upload.service';
import { ImageUploadService } from '../image-upload/image-upload.service';
import { CreateBookingModalService } from '../quotes/create-booking-modal/create-booking-modal.service';
import { IQuotes } from '../quotes/quotes.interface';
import { QuotesService } from '../quotes/quotes.service';
import { NotificationService } from '../_services/notification.service';
import { SingleFormService } from './single-form.service';

@Component({
  selector: 'app-single-form',
  templateUrl: './single-form.component.html',
  styleUrls: ['./single-form.component.scss']
})
export class SingleFormComponent implements OnInit, AfterViewInit {
  static readonly SAVE = 0;
  static readonly SAVE_AND_NEW = 1;
  static readonly SAVE_AND_CLOSE = 2;
  @Input() formGroup!: FormGroup;
  @Input() canDelete = false;
  @Input() saveAndNew = true;
  @Output() ngSubmit = new EventEmitter();
  @ViewChild('toolbar') toolbar!: TemplateRef<any>;
  menu = new BehaviorSubject<TemplateRef<any>>(this.toolbar);
  timedOutCloser!: any;
  afterSave = SingleFormComponent.SAVE;
  uploadingDoc = false;
  uploadingImg = false;
  uploadingIcon = false;
  uploadingDocSubscription: Subscription;
  uploadingImgSubscription: Subscription;
  uploadingIconSubscription: Subscription;
  saveSubscription: Subscription;
  readonly SingleFormComponent = SingleFormComponent;

  protected notificationSrv: NotificationService;

  //
  customUrl!: string;


  constructor(protected injector: Injector,public formSrv: SingleFormService, private dialogSrv: ConfirmationDialogService, private route: ActivatedRoute,
    private router: Router, private docSrv: DocumentsService<IDocuments>, private imgSrv: ImageUploadService, private cmpSrv: CompanyPreferencesService, protected formDialogSrv: FormDialogService,
    private iconSrv: IconUploadService, protected createRenSrv: CreateRentalModalService,protected createBookSrv:CreateBookingModalService,protected quoteSrv:QuotesService<IQuotes>) {
    this.notificationSrv = injector.get(NotificationService);
    }

  ngOnInit(): void {
    this.customUrl = this.router.url.split('/')[1];
    //console.log(this.customUrl);

    this.docSrv.init();
    this.imgSrv.init();
    this.iconSrv.init();
    this.uploadingDocSubscription = this.docSrv.uploading$.subscribe(uploading => {
      this.uploadingDoc = uploading;
    });
    this.uploadingImgSubscription = this.imgSrv.uploading$.subscribe(uploading => {
      this.uploadingImg = uploading;
    });
    this.uploadingIconSubscription = this.iconSrv.uploading$.subscribe(uploading => {
      this.uploadingIcon= uploading;
    });
    this.saveSubscription = this.formSrv.onSave().subscribe((id) => {
      if (this.afterSave === SingleFormComponent.SAVE) {
        if (this.route.snapshot?.url[0]?.path === 'create' && this.formDialogSrv.comesFromModal.getValue()) {//when in modal in creating process was invoked(we do not want that)
          this.router.navigate(['../', id], { relativeTo: this.route });
        }
      } else if (this.afterSave === SingleFormComponent.SAVE_AND_NEW) {
        if (this.route.snapshot?.url[0]?.path === 'create') {
          this.formGroup.reset();
        } else {
          this.router.navigate(['../create'], { relativeTo: this.route });
        }
      } else if (this.afterSave === SingleFormComponent.SAVE_AND_CLOSE) {
        this.router.navigate(['../'], { relativeTo: this.route });
      }
    });
  }


  ngAfterViewInit(): void {
    this.menu.next(this.toolbar);
  }

  ngAfterViewChecked(): void {
    if (this.createRenSrv.callSaveSubject.getValue()){
      this.callSaveRental();
    }
    if (this.createBookSrv.callSaveSubject.getValue()) {
      this.callSaveBooking();
    }
    if (this.quoteSrv.callSaveSubject.getValue()) {
      this.callSaveQuote();
    }
  }

  ngOnDestroy(): void {
    this.uploadingDocSubscription.unsubscribe();
    this.uploadingImgSubscription.unsubscribe();
    this.uploadingIconSubscription.unsubscribe();
    this.saveSubscription.unsubscribe();
  }



  mouseEnter(trigger: any): void {
    if (this.timedOutCloser) {
      clearTimeout(this.timedOutCloser);
    }
    trigger.openMenu();
  }

  mouseLeave(trigger: any): void {
    this.timedOutCloser = setTimeout(() => {
      trigger.closeMenu();
    }, 50);
  }

  save(afterSave: number): void {
    this.formSrv.waitSaveToComplete.next(true);
    console.log(this.router.url);
    if (this.router.url=='/company'){
      this.onSubmitCompanyPref();
    }
    this.afterSave = afterSave;
    this.ngSubmit.emit();
    setTimeout(() => this.formSrv.waitSaveToComplete.next(false) ,3000)
  }

  delete(): void {
    this.dialogSrv.showDialog('Είστε σίγουροι ότι θέλετε να διαγράψετε αυτή την εγγραφή;').subscribe(res => {
      if (res) {
        this.formSrv.triggerDelete();
        this.router.navigate(['../'], { relativeTo: this.route });
      }
    });
  }

  callSaveRental(){
    console.log('save called');
    this.save(0);
    this.createRenSrv.callSaveSubject.next(false);
  }

  callSaveBooking() {
    console.log('save called');
    this.save(0);
    this.createBookSrv.callSaveSubject.next(false);
  }

  callSaveQuote() {
    console.log('save called');
    this.save(0);
    this.quoteSrv.callSaveSubject.next(false);
  }





  onSubmitCompanyPref(): void {
    console.log('a');
    if (!this.formGroup.errors) {
      this.cmpSrv.update(this.formGroup.value).subscribe((res) => {
        this.notificationSrv.showSuccessNotification('Επιτυχής αποθήκευση');
      }, (err => {
        this.notificationSrv.showErrorNotification(err.message);
        throw err;
      })
      );
    }
  }

}
