import { IBooking } from "../booking/booking.interface";
import { IDocuments } from "../documents/documents.interface";
import { IInvoices } from "../invoices/invoices.interface";
import { IRental } from "../rental/rental.interface";
import { IPaymentCustomer } from "./payment-customer.interface";

export interface IPayment {
    id: string;
    created_at: Date;
    updated_at: Date;
    payment_datetime: Date;
    payment_method: string;
  payment_type: string;
  payment_typeEn: string;
  payer: IPaymentCustomer;
    balance: number;
    amount: number;
    reference: string;
    brand_id: any;
    rental_id: any;
    user_id: any;
    station_id: any;
    place: any;
    comments: string;
    sequence_number: string;

  credit_card_number:string;
  credit_card_month:string;
  credit_card_year:string;

  credit_card_month_year: string;

  cheque_number:string;
  cheque_due_date:string;
  bank_transfer_account:string;
  card_type: string;
  foreigner:string;

  payer_id: string;
  payer_type: string;

  documents: IDocuments;

  booking: IBooking;

  conInvoice: IInvoices;
  conRental: IRental;

  IamPayment:string;


}
