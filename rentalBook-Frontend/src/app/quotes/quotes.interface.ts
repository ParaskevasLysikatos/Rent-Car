import { IAgent } from "../agent/agent.interface";
import { IBookingItem } from "../booking-item/booking-item.interface";
import { IBookingSource } from "../booking-source/booking-source.interface";
import { IBooking } from "../booking/booking.interface";
import { ICompany } from "../company/company.interface";
import { IDocuments } from "../documents/documents.interface";
import { IDriver } from "../driver/driver.interface";
import { IPlace } from "../places/place.interface";
import { IStation } from "../stations/station.interface";
import { ISummaryCharges } from "../summary-charges/summary-charges.interface";
import { Tag } from "../tag/tag.interface";
import { ITypes } from "../types/types.interface";
import { IVehicle } from "../vehicle/vehicle.interface";

export interface IQuotes {
  id:string;
  created_at: Date;
  updated_at: string;
  status:string;
  //discount_percentage:string;
  flight: string;
 // agent_voucher:  string;
  //confirmation_number: string;
  excess: string;
  phone: string;
  modification_number: string;
  sequence_number: string;

  sub_account_type: string;
  sub_account_id: string;
  sub_account: any;

  user_id: any;
  company_id: any;
  type_id: any;
  brand_id: any;
  agent_id: any;
  source_id: any;
 // program_id: string;
 // charge_type_id: string;
  cancel_reason_id: string;

  customer_id: string;
  customer_text: string;
  customer_driver: any;
  customer: any;

  checkout_station_id: any;
  checkout_place_id: string;
  checkout_place_text: string;
 // checkout_station_fee: string;
  checkout_comments: string;
  checkout_datetime: string;

  checkin_datetime: string;
  checkin_station_id: any;
  checkin_place_id: string;
  checkin_place_text: string;
 // checkin_station_fee: string;
  checkin_comments: string;

  may_extend: string;
 // estimated_km: string;
  valid_date: string;
  extension_rate: string;
  extra_day: string;

  duration: string;
 // rate: string;
 // vat_included: string;
 // distance: string;
 // distance_rate: string;
 // transport_fee: string;
 // insurance_fee: string;
 // options_fee: string;
 // fuel_fee: string;
 // subcharges_fee: string;
 // rental_fee: string;
 // vat_fee: string;
 // discount: string;
 // voucher: string;
 // total: string;
 // total_net: string;
 // total_paid: string;
 // vat: string;
 // balance: string;
  comments: string;

  type: ITypes;
  station_checkin: IStation;
  station_checkout: IStation;

  checkout_place: any;
  checkin_place: any;
  booking_source: IBookingSource;

  agent :IAgent;
 // vehicle : IVehicle;
  documents: IDocuments;
  tags: Tag[];

  booking: IBooking;
  IamQuote : string;
 // driver: IDriver;
  //company: ICompany;

  //items: IBookingItem;
  summary_charges:ISummaryCharges


}
