import { IRental } from './rental.interface';

export interface IRentalCollection extends IRental {
  results: number;
  total_days: number;
  total_amount: number;

  g_results: number;
  g_total_days: number;
  g_total_amount: number;
}
