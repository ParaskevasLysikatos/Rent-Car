import { IDocuments } from "../documents/documents.interface";
import { IVehicle } from "../vehicle/vehicle.interface";

export interface IVehicleExchanges {
    id: string;
    driver_id :string;

      new_vehicle_type_id :any;
      new_vehicle_id :any;
      new_vehicle_transition_id :string;
      new_vehicle_rental_co_km :string;
      new_vehicle_rental_co_fuel_level :string;

      old_vehicle_id :any;
      old_vehicle_transition_id :string;
      old_vehicle_rental_co_km :string;
      old_vehicle_rental_co_fuel_level :string;
      old_vehicle_rental_ci_km :string;
      old_vehicle_rental_ci_fuel_level :string;

      proximate_datetime :string;
      station_id :any;
      rental_id :any;
      datetime :string;
      place_id :string;
      place_text :string;

      place:any;

      reason :string;
      status :string;
      type :string;

  old_vehicle_source: IVehicle;
  new_vehicle_source: IVehicle;
  old_vehicle_km_diff:string;
  new_vehicle_km_diff:string;
  rental: any;

  documents: IDocuments;
}
