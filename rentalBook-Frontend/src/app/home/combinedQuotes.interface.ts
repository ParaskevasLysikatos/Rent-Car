import { IBookingSource } from "../booking-source/booking-source.interface";
import { ICancelReasons } from "../booking/cancel-reason/cancel-reason.interface";
import { IBrand } from "../brand/brand.interface";
import { ICompany } from "../company/company.interface";
import { ICompanyPreferences } from "../company_preferences/company.interface";
import { IDriver } from "../driver/driver.interface";
import { IOptions } from "../options/options.interface";
import { IRoles } from "../roles/roles.interface";
import { IStation } from "../stations/station.interface";
import { Tag } from "../tag/tag.interface";
import { ITypes } from "../types/types.interface";

export interface ICombinedQuotes {

  sources: IBookingSource[];
  brands: IBrand[];
  tags: Tag[];
  stations: IStation[];
  options: IOptions[];

  groups: ITypes[];
  reason: ICancelReasons[];
  drivers: IDriver[];
  company: ICompany[];

  companyPref: ICompanyPreferences[];
}
