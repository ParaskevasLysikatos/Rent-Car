import { IProfiles } from "../_interfaces/profiles.interface";

export interface IOptions {
    id: string;
    cost_daily: number;
    cost_max: number;
    items_max: number;
    active_daily_cost: boolean;
    default_on: boolean;
    option_type: string;
    order: number;
    code: string;
    icon: string;
   // cost:number; not used
    profiles: IProfiles;

  //start: Date;
 // end: Date;
}
