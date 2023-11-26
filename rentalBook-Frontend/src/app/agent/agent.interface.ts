import { IBookingSource } from "../booking-source/booking-source.interface";
import { IDocuments } from "../documents/documents.interface";
import { IProgram } from "../program/program.interface";

export interface IAgent {
    id: string;
    name: string;
    commission: number;

    booking_source_id: any;
  booking_source: IBookingSource;

    company_id: any;
    sub_agents: any;
    sub_agents_details: any;

    program_id: string;
  program: IProgram;

    comments: string;
    brand_id:any;

    contact_information_id:string;
  contact_information: any;

    sub_contacts: any;

  documents: IDocuments;


}
