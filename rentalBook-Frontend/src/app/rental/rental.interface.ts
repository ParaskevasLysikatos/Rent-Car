import { IAgent } from "../agent/agent.interface";
import { IBookingItem } from "../booking-item/booking-item.interface";
import { IBookingSource } from "../booking-source/booking-source.interface";
import { IDocuments } from "../documents/documents.interface";
import { IDriver } from "../driver/driver.interface";
import { IInvoices } from "../invoices/invoices.interface";
import { IPayment } from "../payment/payment.interface";
import { IProgram } from "../program/program.interface";
import { IStation } from "../stations/station.interface";
import { ISummaryCharges } from "../summary-charges/summary-charges.interface";
import { Tag } from "../tag/tag.interface";
import { ITypes } from "../types/types.interface";
import { IVehicleExchanges } from "../vehicle-exchanges/vehicle-exchanges.interface";
import { IVehicle } from "../vehicle/vehicle.interface";

export interface IRental {

  id: string;
  tags: Tag[];
  user_id: string;
  contact_information_id:string;
  program_id:string;
  status: string;
  duration: string;
 // rate: string;
  extension_rate: string;
  may_extend:string;

 // checkout_station_fee:string;
  checkout_km:string;
  checkout_fuel_level: string;
  checkout_datetime: string;
  checkout_station_id: string;
  checkout_comments: string;

 // checkin_station_fee: string;
  checkin_km: string;
  checkin_fuel_level:string;
  checkin_datetime: string;
  checkin_station_id: string;
  //checkin_place_id: string;
  checkin_comments: string;

  comments: string;
  excess: string;

  sub_account_id:string;
  sub_account_type: string;

  checkout_driver_id: string;
  checkin_driver_id:string;

  extra_day: string;
  checkin_duration:string;
  modification_number: string;

  booking_id:string;
  booked_checkin_datetime: string;

  //vat_included: string;
  cancel_reason_id: string;

  extra_charges: string;//second invoice

  created_at: Date;
  updated_at: string;
  sequence_number: string;
  source_id: string;
  brand_id: string;
  company_id: string;
  driver_id: string;
  phone: string;
  drivers: string;
  agent_id: string;
  sub_account: any;
  confirmation_number: string;
  agent_voucher: string;

  checkout_place: any;
  checkin_place: any;

  flight: string;
  type_id: string;
  vehicle_id: string;

//  manual_agreement: string;
//  charge_type_id: string;
//  distance: string;
//  distance_rate: string;
//  rental_fee: string;
//  transport_fee: string;
//  insurance_fee: string;
//  options_fee: string;
//  fuel_fee: string;
//  subcharges_fee: string;
//  discount: string;
//  total_net: string;
//  vat: string;
 // vat_fee: string;
 // total: string;
//  voucher: string;
 // total_paid: string;
 // balance: string;

  //items: IBookingItem;
  payments: IPayment;
  invoices: IInvoices[];

  vehicle: IVehicle;
  station_checkin: IStation;
  station_checkout: IStation;
  customer: any;
  booking_source: IBookingSource;
  documents: IDocuments;

  exchanges: IVehicleExchanges[];
  getKmDrivenAttribute:string;
  program: IProgram;
  driver:IDriver;
  agent:IAgent;
  type: ITypes;

  summary_charges: ISummaryCharges


  IamRental:string;
  convert: boolean;
}
