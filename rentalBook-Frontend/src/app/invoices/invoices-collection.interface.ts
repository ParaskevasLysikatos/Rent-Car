import { IInvoices } from './invoices.interface';

export interface IInvoicesCollection extends IInvoices {
  total_amount: number;
  results: number;

  g_total_amount: number;
  g_results: number;
}
