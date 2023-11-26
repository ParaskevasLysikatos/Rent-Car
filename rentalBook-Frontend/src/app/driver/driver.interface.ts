import { IBooking } from "../booking/booking.interface";
import { IContact } from "../contact/contact.interface";
import { IDocuments } from "../documents/documents.interface";
import { IInvoices } from "../invoices/invoices.interface";
import { IQuotes } from "../quotes/quotes.interface";
import { IRental } from "../rental/rental.interface";

export interface IDriver {
    id: string;
    notes: string;
    licence_number: string;
    licence_country: string;
    licence_created: string;
    licence_expire: string;
    role:string;
    contact_id: string;
    user:any;
    contact: IContact;
  documents: IDocuments;

  rentals: IRental[];
  bookings: IBooking[];
  quotes: IQuotes[];
  invoices: IInvoices[];
}
