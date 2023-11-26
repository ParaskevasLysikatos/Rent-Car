import { IAgent } from "../agent/agent.interface";
import { IBookingItem } from "../booking-item/booking-item.interface";
import { IBookingSource } from "../booking-source/booking-source.interface";
import { ICompany } from "../company/company.interface";
import { IDocuments } from "../documents/documents.interface";
import { IDriver } from "../driver/driver.interface";
import { IPayment } from "../payment/payment.interface";
import { IRental } from "../rental/rental.interface";
import { IStation } from "../stations/station.interface";
import { ISummaryCharges } from "../summary-charges/summary-charges.interface";
import { Tag } from "../tag/tag.interface";
import { ITypes } from "../types/types.interface";
import { IVehicle } from "../vehicle/vehicle.interface";

export interface IBooking {
  id: string;
  tags: Tag[];
  created_at:Date;
  updated_at: string;
  status: string;
  duration: string;
  flight: string;
  agent_voucher:string;
  confirmation_number:string;
  modification_number:string;
  sequence_number:string;
   //'discount_percentage' => $this->discount_percentage,

   //'driver_text'=> DriverResource::collection($this->customer) ?? null,
  user_id: string;
  company_id:string;
  type_id:string;
  brand_id:string;
  agent_id:string;
  source_id:string;
  program_id:string;
  vehicle_id:string;
  quote_id:string;
 // rate: string;
  contact_information_id:string;
  cancel_reason_id:string;
  charge_type_id:string;

  customer_id:string;
  customer_text:string;
  customer_type:string;
  customer_driver: any;

  checkout_datetime:string;
  checkout_station_id:string;
  checkout_place_id:string;
  checkout_place_text:string;
 // checkout_station_fee:string;
  checkout_comments:string;

  checkin_datetime:string;
  checkin_station_id: string;
  checkin_place_id:string;
  checkin_place_text:string;
 // checkin_station_fee:string;
  checkin_comments:string;

  may_extend:string;
      //'estimated_km' => $this->estimated_km,
      //'valid_date' => $this->valid_date,
 // distance: string;
//  distance_rate: string;
          //'transport_rate' => $this->transport_rate,

 // insurance_fee: string;
 // options_fee: string;
 // fuel_fee: string;
 // subcharges_fee:string;
 // vat_fee:string;
 // transport_fee:string;
 // rental_fee:string;

 // discount:string;
 // voucher:string;
 // total:string;
 // total_net:string;
  //total_paid:string;
 // vat:string;
  //balance:string;

  comments:string;
  //vat_included:string;

  sub_account: any;
  sub_account_type:string;
  sub_account_id:string;

  phone:string;
  extra_day:string;

  extension_rate:string;
  excess:string;

  type: ITypes;

  station_checkin: IStation;
  station_checkout: IStation;

  checkout_place: any;
  checkin_place: any;

  customer: any;
  booking_source: IBookingSource;
  agent:IAgent;
  vehicle:IVehicle;
//  items: IBookingItem;
  payments: IPayment;
  documents: IDocuments;
  rental: IRental;
 // driver: IDriver;
 // company: ICompany;

  summary_charges: ISummaryCharges

  IamBooking:string

  convert:boolean;

}
