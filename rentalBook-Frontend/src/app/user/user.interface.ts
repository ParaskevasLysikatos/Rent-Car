import { IRoles } from "../roles/roles.interface";
import { IStation } from "../stations/station.interface";

export interface IUser {
    id: string;
     name: string;
      username : string;
      email : string;
      phone : string;
      password: string;
      driver_id : any;
      station_id : any;
      station:IStation;
      role_id: string;
}
