import { IAgent } from "../agent/agent.interface";
import { IBookingSource } from "../booking-source/booking-source.interface";
import { IBrand } from "../brand/brand.interface";
import { ICategories } from "../categories/categories.interface";
import { ICharacteristics } from "../characteristics/characteristics.interface";
import { ICompany } from "../company/company.interface";
import { ICompanyPreferences } from "../company_preferences/company.interface";
import { IDriver } from "../driver/driver.interface";
import { IOptions } from "../options/options.interface";
import { IProgram } from "../program/program.interface";
import { IRoles } from "../roles/roles.interface";
import { IStation } from "../stations/station.interface";

export interface ICombinedAgents {

  programs: IProgram[];
  sources: IBookingSource[];
  brands: IBrand[];
  company: ICompany[];
  sub_accounts: IAgent[];
}
