import { IProfiles } from '../_interfaces/profiles.interface';
import { IPlace } from '../places/place.interface';
import { IPeriodicFees } from './periodic-fees.interface';
import { ILicencePlate } from '../licence-plate/licence-plate.interface';
import { IDocuments } from '../documents/documents.interface';
import { IImage } from '../image-upload/image.interface';

export interface IVehicle {
    id: string;

    vin:string;
    manufacturer:string,
    model: string,
    make: string,
    delete_at:string,
    engine:string,
    power: string,
    hp: string,
    status: string,
    status_id: string,

    licence_plates: ILicencePlate[],
    km: string;
    place_text:string ,
    drive_type_id:string ,
    drive_type:string ,
    transmission_type_id:string ,
    transmission:string ,
    type_id: string,
    type:any,
    vehicle_status: any,
    station_id: any,
    place:any,
    fuel_level: string,

    key_code: string,
    keys_quantity: string,
    doors: string,
    seats: string,
    euroclass: string,
    fuel_type_id:string,
    color_type_id:string,
    ownership_type_id:string,
    class_type_id:string,
    warranty_expiration:string,
    purchase_date:string,
    use_type_id:string,


    engine_number:string,
    tank:string,
    pollution:string,
    radio_code:string,
    purchase_amount:string,
    depreciation_rate:string,//aposvesi
    depreciation_rate_year:string,
    sale_amount:string,
    sale_date:string,
    start_stop:string,//system of battery
    buy_back:string,
    first_date_marketing_authorisation:string,
    first_date_marketing_authorisation_gr:string,
    import_to_system:string,
    export_from_system:string,
    forecast_export_from_system:string,
    manufactured_year:string,

    profiles: IProfiles,
    periodic_fees:IPeriodicFees[],

    //create a profile
    first_licence_plate:string;
    first_licence_plate_date:string;

  vehicle_statuses: any[];
  KTEO: IPeriodicFees;
  insurance: IPeriodicFees;

  documents: IDocuments;
  images: IImage;
}
