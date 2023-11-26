import { ICompany } from './company.interface';

export interface ICompanyCollection extends ICompany {
  results: number;
  g_results: number;
}
