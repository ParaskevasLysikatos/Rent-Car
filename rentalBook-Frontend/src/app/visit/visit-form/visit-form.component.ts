import { Component, Injector, OnInit } from '@angular/core';
import { FormArray, FormControl, Validators } from '@angular/forms';
import { IServiceDetails } from 'src/app/service-details/service-details.interface';
import { ServiceDetailsService } from 'src/app/service-details/service-details.service';
import { IServiceStatus } from 'src/app/service-status/service-status.interface';
import { ServiceStatusService } from 'src/app/service-status/service-status.service';
import { IVehicle } from 'src/app/vehicle/vehicle.interface';
import { IVisitCollection } from '../visit-collection.interface';
import { IVisitDetails } from '../visit-details.interface';
import { IVisit } from '../visit.interface';
import { VisitService } from '../visit.service';
import { MatRadioChange } from '@angular/material/radio';
import { AbstractFormComponent } from 'src/app/abstract-form/abstract-form.component';

@Component({
  selector: 'app-visit-form',
  templateUrl: './visit-form.component.html',
  styleUrls: ['./visit-form.component.scss']
})
export class VisitFormComponent extends AbstractFormComponent implements OnInit {
  form = this.fb.group({
    id: [],
    user_id: [],
    date_start: [],
    vehicle_id: [],
    km: [],
    fuel_level: [],
    comments: [],
    visit_details:[[]],
  });

  statusArray!:any;

  visitServiceDetails!:IVisitDetails[];//edit comes from
  visitVehicle!:IVehicle//edit  comes from
  GeneralDetails!:any;
  RentDetails!:any;
  detailsArray=new FormArray([]);
  detailsData:any[] = [];
  licence_plate!:any;//edit comes from

  constructor(protected injector: Injector,protected serviceStatusSrv:ServiceStatusService<IServiceStatus>,
  protected serviceDetailsSrv:ServiceDetailsService<IServiceDetails>,
  protected visit_detailsSrv:VisitService<IVisit>) {
    super(injector);

  }


  ngOnInit(): void {
    super.ngOnInit();
    this.serviceDetailsSrv.get({}, undefined, -1).subscribe(res=>{
      this.GeneralDetails=res.data.filter(s => s.category=='general');
      this.RentDetails=res.data.filter(item=>item.category=='rent');
 });
    this.serviceStatusSrv.get({},undefined,-1).subscribe(res=>{this.statusArray=res});

}


changeStatus(event: MatRadioChange, serviceDetailsId:string, statusId:string){
  if(event.value) {
    const visit_details:any[] = this.form.controls.visit_details.value;
    const visit_detail_index = visit_details.findIndex((visit_detail: IVisitDetails) => visit_detail.service_details_id == serviceDetailsId);
    console.log(visit_detail_index);
    if (visit_detail_index!=-1) {
      visit_details[visit_detail_index].service_status_id = statusId;
    } else {

      visit_details.push({
        service_details_id:serviceDetailsId,
        service_status_id:statusId
      })
    }
    this.form.controls['visit_details'].patchValue(visit_details);
    console.log(this.form.controls['visit_details'].value);
  }
}

getServiceDetailsById (detailId: string) {
  return this.detailsData.find((detail: IVisitDetails) => detail.service_details_id == detailId);
}

}
