import { IProfiles } from "../_interfaces/profiles.interface";

export interface IVehicleStatus {
  id: string;
  slug: string;
 order: string;
  profiles: IProfiles;
}
