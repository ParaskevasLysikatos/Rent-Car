import { IProfiles } from "../_interfaces/profiles.interface";

export interface IBrand {
    id: string;
    slug: string;
    icon: string;
  profiles: IProfiles;
  print_form: any[];
}
