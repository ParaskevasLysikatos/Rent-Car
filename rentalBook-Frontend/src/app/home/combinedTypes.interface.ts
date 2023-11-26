import { ICategories } from "../categories/categories.interface";
import { ICharacteristics } from "../characteristics/characteristics.interface";
import { ICompanyPreferences } from "../company_preferences/company.interface";
import { IDriver } from "../driver/driver.interface";
import { IOptions } from "../options/options.interface";
import { IRoles } from "../roles/roles.interface";
import { IStation } from "../stations/station.interface";

export interface ICombinedTypes {

  characteristics: ICharacteristics[];
  categories: ICategories[];
  options: IOptions[];
}
