import { IBooking } from "../booking/booking.interface";
import { IDriver } from "../driver/driver.interface";
import { IInvoices } from "../invoices/invoices.interface";
import { IQuotes } from "../quotes/quotes.interface";
import { IRental } from "../rental/rental.interface";

export interface ICompany {
    id: string;
    name:string;
    afm:string;
    doy:string;
    country:string;
    city:string;
    job:string;
    phone:string;
    email:string;
    website:string;
    title:string;
    address:string;
    comments:string;
    phone_2:string;
    zip_code:string;
    main:string;
    mite_number:string;
    foreign_afm:string;

  drivers: IDriver[];
  rentals:IRental[];
  bookings:IBooking[];
  quotes:IQuotes[];
  invoices: IInvoices[];

  IamCompany: string;
}
