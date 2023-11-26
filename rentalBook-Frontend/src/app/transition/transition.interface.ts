import { IDocuments } from "../documents/documents.interface";
import { IDriver } from "../driver/driver.interface";
import { IPlace } from "../places/place.interface";
import { IStation } from "../stations/station.interface";
import { ITransitionType } from "../transition-type/transition-type.interface";
import { ITypes } from "../types/types.interface";
import { IUser } from "../user/user.interface";
import { IVehicleStatus } from "../vehicle-status/vehicle-status.interface";
import { IVehicle } from "../vehicle/vehicle.interface";

export interface ITransition {
    id: string;
    type_id :string;
    completed_at :string;
    vehicle_id : any;
    external_party :string;

    co_datetime :string;
    co_station_id :any;
    co_place_id :string;
    co_notes :string;
    co_km :string;
    co_fuel_level :string;
    co_user_id :any;
    co_place_text :string;

    ci_datetime :string;
    ci_station_id :any;
    ci_place_id :string;
    ci_notes :string;
    ci_km :string;
    ci_fuel_level :string;
    ci_user_id :any;
    ci_place_text :string;

    notes: string;
    driver_id :any;
    rental_id :string;

  type:ITransitionType;

  co_user: IUser;
  ci_user: IUser;

  driver: IDriver;

  co_station :IStation;
  ci_station :IStation;

  co_place :IPlace;
  ci_place :IPlace;

  vehicle: IVehicle;

  distance:string;
  status: string;

  //selectors place
  s_co_place: any;
  s_ci_place: any;

  documents: IDocuments;
  documentsCount: string;

}
