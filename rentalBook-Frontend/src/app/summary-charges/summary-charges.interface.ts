import { IBookingItem } from "../booking-item/booking-item.interface";


export interface ISummaryCharges {
  rate: number;
  charge_type_id: string;
  distance: number;
  distance_rate: number;
  discount_percentage:number;// is the discount,discount_fee is calculated both front and back

  rental_fee: number;
  insurance_fee: number;
  options_fee: number;
  fuel_fee: number;
  subcharges_fee:number;
  vat_fee: number;
  transport_fee: number;

  discount: number;
  vat_included: number;
  vat: number;
  voucher: number;
  manual_agreement:string;

  total: number;
  total_net: number;
  total_paid: number;
  balance: number;

  items: IBookingItem[];
}

