import { Component, Injector, OnInit, ViewChild } from '@angular/core';
import { Validators } from '@angular/forms';
import { AbstractFormComponent } from 'src/app/abstract-form/abstract-form.component';
import { IBookingSource } from 'src/app/booking-source/booking-source.interface';
import { BookingSourceService } from 'src/app/booking-source/booking-source.service';
import { IBrand } from 'src/app/brand/brand.interface';
import { BrandService } from 'src/app/brand/brand.service';
import { CombinedService } from 'src/app/home/combined.service';
import { MultilingualFormComponent } from 'src/app/multilingual-form/multilingual-form.component';
import { MyLoadingService } from 'src/app/my-loading/my-loading.service';
import { IProgram } from 'src/app/program/program.interface';
import { ProgramService } from 'src/app/program/program.service';
import { AbstractSelectorComponent } from 'src/app/_selectors/abstract-selector/abstract-selector.component';
import { SpinnerService } from 'src/app/_services/spinner.service';

@Component({
  selector: 'app-agent-form',
  templateUrl: './agent-form.component.html',
  styleUrls: ['./agent-form.component.scss']
})
export class AgentFormComponent extends AbstractFormComponent implements OnInit {
  form = this.fb.group({
    id: [],
    name: [, Validators.required],
    commission:[],
    booking_source_id: [],
    company_id: [],
    sub_agents: [[]],
    program_id: [],
    brand_id: [],
    comments: [],
    contact_information_id:[],
    sub_contacts: [],

    documents:[],
  });

  sub_agents_details:any=[];

  programs:any=[];

  @ViewChild('source', { static: false }) source_id_Ref: AbstractSelectorComponent<any>;
  @ViewChild('brand', { static: false }) brand_id_Ref: AbstractSelectorComponent<any>;
  @ViewChild('company', { static: false }) company_id_Ref: AbstractSelectorComponent<any>;
  @ViewChild('sub', { static: false }) sub_id_Ref: AbstractSelectorComponent<any>;

  constructor(protected injector: Injector, protected programSrv: ProgramService<IProgram>, public spinnerSrv: SpinnerService
    ,public loadSrv: MyLoadingService, public sourceSrv: BookingSourceService<IBookingSource>,
    public brandSrv: BrandService<IBrand>, public combinedSrv: CombinedService) {
    super(injector);
  }


  ngOnInit() {
    //this.loadSrv.loadingOn();
    super.ngOnInit();
    this.combinedSrv.getAgents().subscribe((res) => {
      this.programs = res.programs;
      this.source_id_Ref.selector.data = res.sources;
      this.brand_id_Ref.selector.data = res.brands;
      this.company_id_Ref.selector.data = res.company;
      this.sub_id_Ref.selector.data = res.sub_accounts;
    });
    this.spinnerSrv.hideSpinner();
    setTimeout(() => { this.loadSrv.loadingOff();
      this.spinnerSrv.hideSpinner();
   }, 3000);

    //this.programSrv.get({}, undefined, -1).subscribe(res=>{this.programs=res.data});
  //  this.sourceSrv.get({}, undefined, -1).subscribe(res => { this.source_id_Ref.selector.data = res.data });
    //this.brandSrv.get({}, undefined, -1).subscribe(res => { this.brand_id_Ref.selector.data = res.data });
  }
}
