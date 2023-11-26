import { IProfiles } from "../_interfaces/profiles.interface";

export interface IBookingSource {
  id: string;
  program_id: string;
  brand_id: any;
  agent_id: string;
  slug: string;
  profiles: IProfiles;
}
