import { IStation } from '../stations/station.interface';
import { IProfiles } from '../_interfaces/profiles.interface';

export interface IPlace {
    id: string;
    slug: string;
    stations: IStation[];
    profiles: IProfiles;
    latitude: string;
    longitude: string;
}
