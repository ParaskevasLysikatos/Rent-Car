import { IPlace } from '../places/place.interface';
import { IProfiles } from '../_interfaces/profiles.interface';

export interface IStation {
    id: string;
    code: string;
    address: string;
    city: string;
    country: string;
    zip_code: string;
    phone: string;
    title: string;
    slug: string;
    location_id: any;
    profiles: IProfiles;
    places: IPlace[];
}
