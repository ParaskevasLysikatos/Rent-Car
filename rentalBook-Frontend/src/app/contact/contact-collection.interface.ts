import { IContact } from './contact.interface';

export interface IContactCollection extends IContact {
  results:number;
  g_results: number;
}
