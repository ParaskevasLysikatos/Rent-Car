import { ILicencePlate } from "../licence-plate/licence-plate.interface";
import { IVehicle } from "../vehicle/vehicle.interface";
import { IVisitDetails } from "./visit-details.interface";

export interface IVisit {
    id: string;
    user_id: string;
    date_start: string;
    vehicle_id: string;
    km: string;
    fuel_level: string;
    comments: string;
    visit_details:IVisitDetails[];
    vehicle:IVehicle;
  }
