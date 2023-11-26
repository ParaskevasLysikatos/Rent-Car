import { IBrand } from "../brand/brand.interface";
import { IPaymentCustomer } from "../payment/payment-customer.interface";
import { IRental } from "../rental/rental.interface";
import { IInstance } from "./instance.interface";

export interface IInvoices {
    id: string;
    type: string;
    range: string;
    date: string;
    fpa: string;
    discount: string;
    datePayed: string;
    notes: string;
    payment_way: string;
    invoicee_type: string;
    invoicee_id: string;
  invoicee: IPaymentCustomer;
    sub_discount_total: string;
    fpa_perc: string;
    final_fpa: string;
    final_total: string;
    brand_id: any;
    station_id: any;
    sequence_number: string;
    total: string;
    rental_id: any;
    sent_to_aade: string;
    instance:IInstance;
    brand:IBrand;
    items: any;
    payment_id: any[];
  IamInvoice: string;
}
