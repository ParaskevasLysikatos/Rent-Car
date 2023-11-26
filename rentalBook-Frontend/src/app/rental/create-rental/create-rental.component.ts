import { I } from '@angular/cdk/keycodes';
import { Component, Injector, TemplateRef, ViewChild, AfterViewInit } from '@angular/core';
import moment from 'moment';
import { filter } from 'rxjs/internal/operators/filter';
import { IBookingItem } from 'src/app/booking-item/booking-item.interface';
import { CreateFormComponent } from 'src/app/create-form/create-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { RentalFormComponent } from '../rental-form/rental-form.component';
import { IRental } from '../rental.interface';
import { RentalService } from '../rental.service';

@Component({
  selector: 'app-create-rental',
  templateUrl: './create-rental.component.html',
  styleUrls: ['./create-rental.component.scss'],
  providers: [{ provide: ApiService, useClass: RentalService }]
})
export class CreateRentalComponent extends CreateFormComponent<IRental> {
  @ViewChild(RentalFormComponent, { static: true }) formComponent!: RentalFormComponent;
  currentDate = moment().format('YYYY-MM-DD HH:mm');
  nextDate = moment().add(1, 'days').format('YYYY-MM-DD HH:mm');

  rentalCreateOneTime: boolean = true;
  currUserStationO: boolean = false;
  currUserStationI: boolean = false;
  compPrefComplete: boolean = false;

  ngOnInit(): void {
    super.ngOnInit();
  }

  // submitted = (res) => {
  //   // if (this.formComponent.form.untouched) {
  //   //   this.formComponent.ShowCheckbox();
  //   // }
  // }

  ngAfterViewInit() {
   // this.formComponent.loadSrv.loadingOn();
    if (this.formComponent.createRenSrv.createRenSubject.getValue()) {//comes from booking
      //summary options must wait options in form init to be completed first!!!
      this.formComponent.$itemsLoad.pipe(filter(value => value)).subscribe(() => {// itemsLoad must come true to begin
        // all the form from booking
        this.formComponent.form.patchValue(this.formComponent.createRenSrv.createRenSubject.getValue());//give booking form values

        //payers
        this.formComponent.payers = this.formComponent.createRenSrv.payersSub.getValue();//give payers

        //summary charges and init options
        // this.formComponent.items.patchValue(this.formComponent.createRenSrv.summaryChargesItemsSub.getValue());
        //  this.formComponent.form.controls.summary_charges.patchValue(this.formComponent.createRenSrv.summaryChargesSub.getValue());
        // this.formComponent.form.get('summary_charges.items').patchValue(this.formComponent.createRenSrv.summaryChargesItemsSub.getValue());
        this.formComponent.form.controls.options.patchValue(this.nullifyOptionsId(this.formComponent.createRenSrv.summaryChargesItemsSub.getValue()));
        console.log(this.formComponent.createRenSrv.summaryChargesSub.getValue());
        this.formComponent.summary_options = this.formComponent.createRenSrv.summaryChargesSub.getValue();

        //tag
        this.formComponent.tag_Ref.tags.push(...this.formComponent.form.controls.tags.value);

        //check-in-out datetime
        this.formComponent.form.controls.checkout_datetime.patchValue(this.currentDate);
        this.formComponent.checkout_datetime = this.formComponent.form.controls.checkout_datetime.value;
        this.formComponent.checkOutDate.timepickerControl.patchValue(moment(this.currentDate).format('HH:mm'));
        this.formComponent.checkin_datetime = this.formComponent.form.controls.checkin_datetime.value;
        this.formComponent.checkInDate.timepickerControl.patchValue(moment(this.formComponent.form.controls.checkin_datetime.value).format('HH:mm'));
        if (this.formComponent.checkin_datetime <= this.formComponent.checkout_datetime) {// when out has overcome checkin
          this.formComponent.form.controls.checkin_datetime.patchValue(this.nextDate);
          this.formComponent.checkin_datetime = this.formComponent.form.controls.checkin_datetime.value;
          this.formComponent.checkInDate.timepickerControl.patchValue(moment(this.nextDate).format('HH:mm'));
        }
        this.formComponent.changeDate();

        this.formComponent.form.controls.status.patchValue(null);//no value on create rental

        //station
        this.formComponent.checkout_station_id = this.formComponent.createRenSrv.stationOutSub.getValue();
        if (this.formComponent.createRenSrv.stationOutSub.getValue() != null) {
          this.formComponent.stationOutEventInit(this.formComponent.createRenSrv.stationOutSub.getValue());
        } else {
          this.formComponent.stationOutComplete = true;
        }

        this.formComponent.checkin_station_id = this.formComponent.createRenSrv.stationInSub.getValue();
        if (this.formComponent.createRenSrv.stationInSub.getValue() != null) {
          this.formComponent.stationInEventInit(this.formComponent.createRenSrv.stationInSub.getValue());
        } else {
          this.formComponent.stationInComplete = true;
        }


        this.formComponent.company_id = this.formComponent.createRenSrv.createRenSubject.getValue().company_id;
        if (this.formComponent.createRenSrv.createRenSubject.getValue().company_id){
          this.formComponent.companyEventInit(this.formComponent.createRenSrv.createRenSubject.getValue().company_id);
        }else{
          this.formComponent.companyComplete = true;
        }


        //agent
        this.formComponent.agent_id = this.formComponent.createRenSrv.agentSub.getValue();
        //sub account
        this.formComponent.sub_account_id = this.formComponent.createRenSrv.createRenSubject.getValue().sub_account_id;
        this.formComponent.sub_account_type = this.formComponent.createRenSrv.createRenSubject.getValue().sub_account_type;
        if (this.formComponent.createRenSrv.agentSub.getValue() != null) {
          this.formComponent.agentEventInit(this.formComponent.createRenSrv.agentSub.getValue());
          // this.formComponent.subAccountEvent();  //must be initiated after agent event!!
        } else {
          this.formComponent.agentComplete = true;
        }

        // agent must be before source

        //source
        this.formComponent.source_id = this.formComponent.createRenSrv.sourceSub.getValue();
        if (this.formComponent.createRenSrv.sourceSub.getValue() != null) {
          this.formComponent.sourceEventInit(this.formComponent.createRenSrv.sourceSub.getValue());
        } else {
          this.formComponent.sourceComplete = true;
        }

        //vehicle and must be before group event
        this.formComponent.vehicleData = this.formComponent.createRenSrv.vehicleSub.getValue();
        this.formComponent.vehicle_id = this.formComponent.vehicleData?.id;

        if (this.formComponent.createRenSrv.vehicleSub.getValue()) { //has vehicle
          this.formComponent.form.controls.checkout_km.patchValue(this.formComponent.selectorSrv.searchVehicleTemp.getValue().km);
          this.formComponent.form.controls.checkout_fuel_level.patchValue(this.formComponent.selectorSrv.searchVehicleTemp.getValue().fuel_level);
        }

        //type
        this.formComponent.type_id = this.formComponent.createRenSrv.typeSub.getValue();
        if (this.formComponent.createRenSrv.typeSub.getValue() != null) {
          this.formComponent.groupEventInit(this.formComponent.createRenSrv.typeSub.getValue());
        } else {
          this.formComponent.groupComplete = true;
        }


        //driver
        if (this.formComponent.createRenSrv.driverSub.getValue().id) {
          this.formComponent.form.controls.driver_id.patchValue(this.formComponent.createRenSrv.driverSub.getValue().id);
          this.formComponent.driver_id = this.formComponent.createRenSrv.driverSub.getValue().id;
          if (this.formComponent.createRenSrv.driverSub.getValue().id != null) {
            this.formComponent.driverEventInit(this.formComponent.createRenSrv.driverSub.getValue().id);
          } else {
            this.formComponent.driverComplete = true;
          }
          this.formComponent.form.controls.phone.patchValue(this.formComponent.createRenSrv.driverSub.getValue().phone);
        } else {
          this.formComponent.driverComplete = true;
        }


        this.formComponent.form.controls.id.patchValue(null);//cause is new rental, not the booking id
        this.formComponent.seeValuesConsole();
        this.formComponent.form.controls.convert.patchValue(true);

        // not exists in booking default values
        this.formComponent.form.controls.created_at.patchValue(this.currentDate);
        let currentUser = JSON.parse(localStorage.getItem('loggedUser'));
        this.formComponent.form.controls.user_id.patchValue(currentUser.id);
        //Πληροφορίες οχήματος
        this.formComponent.form.controls.checkout_driver_id.patchValue(currentUser.driver_id);
        // Πληροφορίες Παραλαβής οχήματος
        this.formComponent.form.controls.checkin_driver_id.patchValue(currentUser.driver_id);
      });
      // this.formComponent.printRentalSrv.afterDataLoadSubject.next(true);
    }
    else {//comes from new rental (classic)

      this.formComponent.$itemsLoad.pipe(filter(value => value)).subscribe(() => {// itemsLoad must come true to begin

        //Γενικές Πληροφορίες
        this.formComponent.form.controls.created_at.patchValue(this.currentDate);
        let currentUser = JSON.parse(localStorage.getItem('loggedUser'));
        this.formComponent.form.controls.user_id.patchValue(currentUser.id);

        //Πληροφορίες Παράδοσης   //Πληροφορίες Παραλαβής
        this.formComponent.form.controls.checkout_station_id.patchValue(currentUser.station_id);
        this.formComponent.checkout_station_id = currentUser.station_id;
        this.formComponent.form.controls.checkin_station_id.patchValue(currentUser.station_id);
        this.formComponent.checkin_station_id = currentUser.station_id;
        this.formComponent.stationSrv.edit(currentUser.station_id).subscribe(res => {
          this.formComponent.form.controls.checkout_place.patchValue({ //choose first filtered place
            id: res?.places[0]?.id,
            name: res?.places[0]?.profiles?.el?.title
          });
          this.formComponent.form.controls.checkin_place.patchValue({ //choose first filtered place
            id: res?.places[0]?.id,
            name: res?.places[0]?.profiles?.el?.title
          });
          this.currUserStationO = true;
          this.currUserStationI = true;
        });


        //Πληροφορίες οχήματος
        this.formComponent.form.controls.checkout_driver_id.patchValue(currentUser.driver_id);
        // Πληροφορίες Παραλαβής οχήματος
        this.formComponent.form.controls.checkin_driver_id.patchValue(currentUser.driver_id);

        //company preferences
        this.formComponent.form.controls.source_id.patchValue(this.formComponent.companyPrefData.rental_source_id);
        this.formComponent.source_id = this.formComponent.companyPrefData.rental_source_id;
        this.formComponent.sourceEventInit(this.formComponent.companyPrefData.rental_source_id);
        this.formComponent.form.get('summary_charges.vat').patchValue(this.formComponent.companyPrefData.vat);//fpa
        this.compPrefComplete = true;
        //will come from η εταιρια μου

        //Πληροφορίες Παράδοσης datetime init
        this.formComponent.form.controls.checkout_datetime.patchValue(this.currentDate);
        this.formComponent.checkout_datetime = this.formComponent.form.controls.checkout_datetime.value;
        this.formComponent.checkOutDate.timepickerControl.patchValue(moment(this.currentDate).format('HH:mm'));

        //Πληροφορίες Παραλαβής datetime init
        this.formComponent.form.controls.checkin_datetime.patchValue(this.nextDate)// next date
        this.formComponent.checkin_datetime = this.formComponent.form.controls.checkin_datetime.value;
        this.formComponent.checkInDate.timepickerControl.patchValue(moment(this.nextDate).format('HH:mm'));

        this.formComponent.form.controls.duration.patchValue(1);

        // default Πληροφορίες διάρκειας and summary charges
        this.formComponent.form.controls.extension_rate.patchValue(0);
        this.formComponent.form.get('summary_charges.rate').patchValue(0);
        this.formComponent.form.get('summary_charges.distance').patchValue(0);
        this.formComponent.form.get('summary_charges.distance_rate').patchValue(0);
        this.formComponent.form.get('summary_charges.discount').patchValue(0);

        this.formComponent.form.get('summary_charges.rental_fee').patchValue(0);
        this.formComponent.form.get('summary_charges.transport_fee').patchValue(0);
        this.formComponent.form.get('summary_charges.insurance_fee').patchValue(0);
        this.formComponent.form.get('summary_charges.options_fee').patchValue(0);
        this.formComponent.form.get('summary_charges.fuel_fee').patchValue(0);
        this.formComponent.form.get('summary_charges.subcharges_fee').patchValue(0);
        this.formComponent.form.get('summary_charges.vat_fee').patchValue(0);
        this.formComponent.form.get('summary_charges.total').patchValue(0);
        this.formComponent.form.get('summary_charges.total_net').patchValue(0);

        this.formComponent.form.get('payments').patchValue([]);
        this.formComponent.form.get('options').patchValue([]);
      });
      // this.formComponent.printRentalSrv.afterDataLoadSubject.next(true);// because is used in formComponent
    }
  }


  ngAfterViewChecked() {
    let sourceCpl = this.formComponent.sourceComplete;
    //when comes from booking --///
    let driverCpl = this.formComponent.driverComplete;
    let stationOutCpl = this.formComponent.stationOutComplete;
    let stationInCpl = this.formComponent.stationInComplete;
    let agentCpl = this.formComponent.agentComplete;
    let groupCpl = this.formComponent.groupComplete;
    let optionsCpl = this.formComponent.optionsItemsComplete;
    let companyCpl = this.formComponent.companyComplete;
    //-----//

    // wait until all  after-view-init methods complete and run one time this if to activate fom component calcs
    if (this.rentalCreateOneTime && sourceCpl && this.currUserStationO
      && this.currUserStationI && this.compPrefComplete) {
      this.formComponent.payersCollection();// here needed, on convert I pass them
      this.formComponent.printRentalSrv.afterDataLoadSubject.next(true);
      this.rentalCreateOneTime = false;
      console.log('rental create afterViewInit activated');
    }
    // come from quote convert
    else if (this.rentalCreateOneTime && sourceCpl && driverCpl
      && stationInCpl && stationOutCpl && agentCpl && groupCpl && optionsCpl  && companyCpl) {
      //methods has change their user values to defaults
      this.formComponent.saveItems('rental_charges', 'summary_charges.rental_fee');
      this.formComponent.printRentalSrv.afterDataLoadSubject.next(true);
      this.rentalCreateOneTime = false;
      console.log('rental create from booking  activated');
    }
  }


  nullifyOptionsId(array: IBookingItem[]): IBookingItem[] {
    array.forEach((item) => item.id = '');
    return array;
  }



}
