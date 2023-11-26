import { IProfiles } from "../_interfaces/profiles.interface";

export interface ICharacteristics {
    id: string;
    slug: string;
    profiles: IProfiles;
    icon: string;
}
