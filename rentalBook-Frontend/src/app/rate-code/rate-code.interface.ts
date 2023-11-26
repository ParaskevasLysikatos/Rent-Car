import { IProfiles } from "../_interfaces/profiles.interface";

export interface IRateCode {
    id: string;
  slug: string;
  profiles: IProfiles;
}
