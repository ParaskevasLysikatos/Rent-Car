import { IDocuments } from "../documents/documents.interface";

export interface ILicencePlate {
    id: string;
    vehicle_id: string;
    licence_plate: string;
    registration_date: string;
  documents: IDocuments;

}
