import { IQuotes } from './quotes.interface';

export interface IQuotesCollection extends IQuotes {
  results: number;
  total_days: number;
  total_amount: number;

  g_results: number;
  g_total_days: number;
  g_total_amount: number;
}
