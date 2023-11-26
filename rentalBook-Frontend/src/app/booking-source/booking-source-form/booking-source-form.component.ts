import { Component, Injector, OnInit, ViewChild } from '@angular/core';
import { Validators } from '@angular/forms';
import { IBrand } from 'src/app/brand/brand.interface';
import { BrandService } from 'src/app/brand/brand.service';
import { CombinedService } from 'src/app/home/combined.service';
import { MultilingualFormComponent } from 'src/app/multilingual-form/multilingual-form.component';
import { IProgram } from 'src/app/program/program.interface';
import { ProgramService } from 'src/app/program/program.service';
import { AbstractSelectorComponent } from 'src/app/_selectors/abstract-selector/abstract-selector.component';

@Component({
  selector: 'app-booking-source-form',
  templateUrl: './booking-source-form.component.html',
  styleUrls: ['./booking-source-form.component.scss']
})
export class BookingSourceFormComponent extends MultilingualFormComponent implements OnInit {
  form = this.fb.group({
  id: null,
  program_id:[],
  brand_id: [],
  agent_id: [],
  slug: [],
  });

  programs:any=[];
  @ViewChild('brand', { static: false }) brand_id_Ref: AbstractSelectorComponent<any>;
  @ViewChild('agent', { static: false }) agent_id_Ref: AbstractSelectorComponent<any>;

  constructor(protected injector: Injector, protected programSrv: ProgramService<IProgram>, public brandSrv: BrandService<IBrand>, public combinedSrv: CombinedService) {
    super(injector);
  }


  ngOnInit() {
    super.ngOnInit();
    this.combinedSrv.getBookingSources().subscribe((res) => {
      this.brand_id_Ref.selector.data = res.brands
      this.agent_id_Ref.selector.data = res.agent;
      this.programs = res.programs;
    });
    // this.brandSrv.get({}, undefined, -1).subscribe(res => { this.brand_id_Ref.selector.data = res.data })
    // this.programSrv.get({}, undefined, -1).subscribe(res=>{this.programs=res.data});
    this.spinnerSrv.hideSpinner();
  }

}
