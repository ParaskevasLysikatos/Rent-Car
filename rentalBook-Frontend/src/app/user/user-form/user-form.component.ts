import { Component, Injector, OnInit, ViewChild } from '@angular/core';
import { Validators } from '@angular/forms';
import { AbstractFormComponent } from 'src/app/abstract-form/abstract-form.component';
import { CompanyPreferencesService } from 'src/app/company_preferences/company.service';
import { CombinedService } from 'src/app/home/combined.service';
import { IRoles } from 'src/app/roles/roles.interface';
import { RolesService } from 'src/app/roles/roles.service';
import { IStation } from 'src/app/stations/station.interface';
import { StationService } from 'src/app/stations/station.service';
import { AbstractSelectorComponent } from 'src/app/_selectors/abstract-selector/abstract-selector.component';
import { AuthService } from 'src/app/_services/auth.service';

@Component({
  selector: 'app-user-form',
  templateUrl: './user-form.component.html',
  styleUrls: ['./user-form.component.scss']
})
export class UserFormComponent extends AbstractFormComponent implements OnInit {
  form = this.fb.group({
    id: [],
    name: [,Validators.required],
    password: [],
    username : [,Validators.required],
    email : [,Validators.required],
    phone : [],
    driver_id : [,Validators.required],
    station_id: [, Validators.required],
    role_id:[,Validators.required],
  });

  userRoles: any = [];
  hide = true;
  compPrefData: any = [];
  @ViewChild('station', { static: false }) station_id_Ref: AbstractSelectorComponent<any>;
  @ViewChild('driverEmp', { static: false }) driverEmp_id_Ref: AbstractSelectorComponent<any>;

  constructor(protected injector: Injector,protected rolesSrv:RolesService<IRoles>,
    private compPrefSrv: CompanyPreferencesService, protected authSrv: AuthService, public stationSrv: StationService<IStation>, public combinedSrv: CombinedService) {
    super(injector);
  }

  ngOnInit() {
    super.ngOnInit();
    this.combinedSrv.getUsers().subscribe((res) => {
      this.userRoles = res.roles;
      this.station_id_Ref.selector.data = res.stations;
      this.driverEmp_id_Ref.selector.data = res.driversEmp;
      this.compPrefData = res.companyPref[res.companyPref.length-1];
      if (!this.form.controls.station_id.value) {
        this.form.controls.station_id.patchValue(this.compPrefData.station_id);
      }
    });
  // this.rolesSrv.get({}, undefined, -1).subscribe((res) => this.userRoles = res.data);

    // this.stationSrv.get({}, undefined, -1).subscribe(res => {
    //   this.station_id_Ref.selector.data = res.data;
    // });

  //   this.compPrefSrv.edit().subscribe((res) => { this.compPrefData = res;
  //     if (!this.form.controls.station_id.value) {
  //       this.form.controls.station_id.patchValue(this.compPrefData.station_id);
  //     }
  //  });


  }


  get password() { return this.form.get('password'); }

}
