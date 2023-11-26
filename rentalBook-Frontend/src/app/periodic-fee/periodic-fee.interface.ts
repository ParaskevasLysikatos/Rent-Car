import { IDocuments } from "../documents/documents.interface";

export interface IPeriodicFee {
  id: string;
  periodic_fee_type_id:string;
  vehicle_id:string;
  title:string;
  description:string;
  fee:string;
  date_start:string;
  date_expiration:string;
  date_payed:string;
  fee_type:string;
  documents: IDocuments
}
