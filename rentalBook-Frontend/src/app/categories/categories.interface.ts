import { IProfiles } from "../_interfaces/profiles.interface";

export interface ICategories {
  id: string;
  slug: string;
  profiles: IProfiles;
  icon: string;
}
