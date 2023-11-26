import { IAgent } from "../agent/agent.interface";
import { IBookingSource } from "../booking-source/booking-source.interface";
import { ICancelReasons } from "../booking/cancel-reason/cancel-reason.interface";
import { IBrand } from "../brand/brand.interface";
import { ICategories } from "../categories/categories.interface";
import { ICharacteristics } from "../characteristics/characteristics.interface";
import { IClassType } from "../class-type/class-type.interface";
import { IColorType } from "../color-type/color-type.interface";
import { ICompany } from "../company/company.interface";
import { ICompanyPreferences } from "../company_preferences/company.interface";
import { IDriveType } from "../drive-type/drive-type.interface";
import { IDriver } from "../driver/driver.interface";
import { IFuelType } from "../fuel-type/fuel-type.interface";
import { IOptions } from "../options/options.interface";
import { IOwnershipType } from "../ownership-type/ownership-type.interface";
import { IPaymentMethod } from "../payment/payment-method.interface";
import { IPayment } from "../payment/payment.interface";
import { IPeriodicFeeTypes } from "../periodic-fee-types/periodic-fee-types.interface";
import { IPlace } from "../places/place.interface";
import { IProgram } from "../program/program.interface";
import { IPayers } from "../rental/rental-form/rental-form.component";
import { IRental } from "../rental/rental.interface";
import { IRoles } from "../roles/roles.interface";

import { IStation } from "../stations/station.interface";
import { Tag } from "../tag/tag.interface";
import { ITransmissionType } from "../transmission-type/transmission-type.interface";
import { ITypes } from "../types/types.interface";
import { IUseType } from "../use-type/use-type.interface";
import { IUser } from "../user/user.interface";
import { IVehicleStatus } from "../vehicle-status/vehicle-status.interface";
import { IVehicle } from "../vehicle/vehicle.interface";

export interface ICombined {
  sources: IBookingSource[];
  brands: IBrand[];
  tags: Tag[];
  stations:IStation[];
  places:IPlace[];
  groups:ITypes[];
  reason: ICancelReasons[];
  drivers:IDriver[];
  driversEmp:IDriver[];
  company: ICompany[];
  agent:IAgent[];
  roles:IRoles[];
  users: IUser[];
  vehicles:IVehicle[];

  characteristics:ICharacteristics[];
  categories:ICategories[];
  options: IOptions[];

  rentals:IRental[];
  customers:IPayers[];
  payments:IPayment[];

  class:IClassType[];
  fuel: IFuelType[];
  ownership:IOwnershipType[];
  use:IUseType[];
  periodicFee_types:IPeriodicFeeTypes[];
  transmission:ITransmissionType[];
  drive_type: IDriveType[];
  color_type:IColorType[];
  vehicleStatus: IVehicleStatus[];

  getMethods: IPaymentMethod[];
  getCards: any[];

  programs:IProgram[];
  companyPref: ICompanyPreferences[];

}
