import { ICompanyPreferences } from "../company_preferences/company.interface";
import { IDriver } from "../driver/driver.interface";
import { IRoles } from "../roles/roles.interface";
import { IStation } from "../stations/station.interface";

export interface ICombinedUsers {

  roles: IRoles[];
  stations: IStation[];
  driversEmp: IDriver[];
  companyPref: ICompanyPreferences[];
}
