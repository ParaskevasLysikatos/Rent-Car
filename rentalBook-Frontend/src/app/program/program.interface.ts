import { IProfiles } from "../_interfaces/profiles.interface";

export interface IProgram {
  id: string;
  name: string;
  profiles: IProfiles;
}
