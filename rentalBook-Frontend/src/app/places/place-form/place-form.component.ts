import { Component, Injector, OnInit, ViewChild } from '@angular/core';
import {  Validators } from '@angular/forms';
import { MultilingualFormComponent } from 'src/app/multilingual-form/multilingual-form.component';
import { IStation } from 'src/app/stations/station.interface';
import { StationService } from 'src/app/stations/station.service';
import { AbstractSelectorComponent } from 'src/app/_selectors/abstract-selector/abstract-selector.component';

@Component({
  selector: 'app-place-form',
  templateUrl: './place-form.component.html',
  styleUrls: ['./place-form.component.scss']
})
export class PlaceFormComponent extends MultilingualFormComponent implements OnInit {
  form = this.fb.group({
    id: null,
    stations: [[], Validators.required],
    latitude: [null, Validators.required],
    longitude: [null, Validators.required]
  });

  @ViewChild('station', { static: false }) station_id_Ref: AbstractSelectorComponent<any>;


  constructor(protected injector: Injector, public stationSrv: StationService<IStation>) {
    super(injector);
  }

  ngOnInit(): void {
      super.ngOnInit();
    this.stationSrv.get({}, undefined, -1).subscribe(res => {
      this.station_id_Ref.selector.data = res.data;
    });
  }
}
