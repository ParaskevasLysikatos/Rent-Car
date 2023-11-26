import { Observable } from 'rxjs';
import { Component, Input, OnInit, ViewChild } from '@angular/core';
import { SignaturePad } from 'angular2-signaturepad';
import { NotificationService } from 'src/app/_services/notification.service';
import { IRental } from '../rental.interface';
import { RentalSignatureService } from './rental-signature.service';
import { DomSanitizer, SafeUrl } from '@angular/platform-browser';

@Component({
  selector: 'app-rental-signature',
  templateUrl: './rental-signature.component.html',
  styleUrls: ['./rental-signature.component.scss']
})
export class RentalSignatureComponent implements OnInit {

  @ViewChild('signaturePad1',{ static: false}) signaturePad1: SignaturePad;
  @ViewChild('signaturePad2', { static: false }) signaturePad2: SignaturePad;
  @ViewChild('signaturePad3', { static: false }) signaturePad3: SignaturePad;
  @Input() obj: IRental;

  signatureImg1!: string;
  signatureImg2!: string;
  signatureImg3!: string;

  signaturePadOptions: Object = {
    'minWidth': 2,
    'canvasWidth': 400,
    'canvasHeight': 300
  };

  constructor(private notificationSrv: NotificationService, private signatureSrv: RentalSignatureService, private domSanitizer: DomSanitizer) { }

  ngOnInit() {

  }

  ngAfterViewInit(){
    // this.signaturePad is now available
    this.signaturePad1.set('minWidth', 2);
    this.signaturePad1.clear();

    this.signaturePad2.set('minWidth', 2);
    this.signaturePad2.clear();

    this.signaturePad3.set('minWidth', 2);
    this.signaturePad3.clear();
  }


  drawComplete1() {
    // will be notified of szimek/signature_pad's onEnd event
  //  console.log(this.signaturePad1.toDataURL());
  }

  drawStart1() {
    // will be notified of szimek/signature_pad's onBegin event
    console.log('begin drawing');
  }

  clearSignature1() {
    this.signaturePad1.clear();
  }

  savePad1(missingUrl:string) {
    if (this.signaturePad1.isEmpty()) {
      return this.notificationSrv.showErrorNotification('Please provide a signature 1 first.');
    }
    var data1 = this.signaturePad1.toDataURL('image/png');
    var ren1 = this.obj;
    this.signatureSrv.save(missingUrl, data1, ren1).subscribe(() => {
      this.notificationSrv.showSuccessNotification('Saved excess successfully');
    },err=>{
      this.notificationSrv.showErrorNotification(err);
    });
  }

  DeleteSignature1(missingUrl: string){
    var ren1 = this.obj;
    this.signatureSrv.delete(missingUrl,ren1).subscribe(() => {
      this.notificationSrv.showSuccessNotification('Deleted excess successfully');
    },err => {
      this.notificationSrv.showErrorNotification(err);
    });
  }

  SeeSignature1(missingUrl: string){
    var ren1 = this.obj;
    this.signatureSrv.seeImg(missingUrl, ren1).subscribe((res) => {
      this.signatureImg1 = res.replace('data:image/jpeg;base64,', ''); //this.domSanitizer.bypassSecurityTrustUrl(res);
     setTimeout(() => this.signatureImg1 = '', 5000);
    }, err => {
      this.notificationSrv.showErrorNotification(' Image for excess signature not found');
    });
  }


  // deuteros -----------------------------------------------

  clearSignature2() {
    this.signaturePad2.clear();
  }

  savePad2(missingUrl: string) {
    if (this.signaturePad2.isEmpty()) {
      return this.notificationSrv.showErrorNotification('Please provide a signature 2 first.');
    }
    var data1 = this.signaturePad2.toDataURL('image/png');
    var ren1 = this.obj;
    this.signatureSrv.save(missingUrl, data1, ren1).subscribe(() => {
      this.notificationSrv.showSuccessNotification('Saved main successfully');
    }, err => {
      this.notificationSrv.showErrorNotification(err);
    });
  }

  DeleteSignature2(missingUrl: string) {
    var ren1 = this.obj;
    this.signatureSrv.delete(missingUrl, ren1).subscribe(() => {
      this.notificationSrv.showSuccessNotification('Deleted main successfully');
    }, err => {
      this.notificationSrv.showErrorNotification(err);
    });
  }

  SeeSignature2(missingUrl: string) {
    var ren1 = this.obj;
    this.signatureSrv.seeImg(missingUrl, ren1).subscribe((res) => {
      this.signatureImg2 = res.replace('data:image/jpeg;base64,', ''); //this.domSanitizer.bypassSecurityTrustUrl(res);
      setTimeout(() => this.signatureImg2 = '', 5000);
    }, err => {
      this.notificationSrv.showErrorNotification(' Image for main signature not found');
    });
  }

  //tritos---------------------------------------

  clearSignature3() {
    this.signaturePad3.clear();
  }

  savePad3(missingUrl: string) {
    if (this.signaturePad3.isEmpty()) {
      return this.notificationSrv.showErrorNotification('Please provide a signature 3 first.');
    }
    var data1 = this.signaturePad3.toDataURL('image/png');
    var ren1 = this.obj;
    this.signatureSrv.save(missingUrl, data1, ren1).subscribe(() => {
      this.notificationSrv.showSuccessNotification('Saved second driver successfully');
    }, err => {
      this.notificationSrv.showErrorNotification(err);
    });
  }

  DeleteSignature3(missingUrl: string) {
    var ren1 = this.obj;
    this.signatureSrv.delete(missingUrl, ren1).subscribe(() => {
      this.notificationSrv.showSuccessNotification('Deleted second driver successfully');
    }, err => {
      this.notificationSrv.showErrorNotification(err);
    });
  }

  SeeSignature3(missingUrl: string) {
    var ren1 = this.obj;
    this.signatureSrv.seeImg(missingUrl, ren1).subscribe((res) => {
      this.signatureImg3 = res.replace('data:image/jpeg;base64,', ''); //this.domSanitizer.bypassSecurityTrustUrl(res);
      setTimeout(() => this.signatureImg3 = '', 5000);
    }, err => {
      this.notificationSrv.showErrorNotification(' Image for second driver signature not found');
    });
  }

}
