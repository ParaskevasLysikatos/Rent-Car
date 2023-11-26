import { IBrand } from "../brand/brand.interface";
import { ICategories } from "../categories/categories.interface";
import { ICharacteristics } from "../characteristics/characteristics.interface";
import { ICompanyPreferences } from "../company_preferences/company.interface";
import { IDriver } from "../driver/driver.interface";
import { IOptions } from "../options/options.interface";
import { IPayment } from "../payment/payment.interface";
import { IPayers } from "../rental/rental-form/rental-form.component";
import { IRental } from "../rental/rental.interface";
import { IRoles } from "../roles/roles.interface";
import { IStation } from "../stations/station.interface";

export interface ICombinedInvoices {

  brands: IBrand[];
  stations: IStation[];
  rentals: IRental[];
  customers: IPayers[];
  payments: IPayment[];

}
