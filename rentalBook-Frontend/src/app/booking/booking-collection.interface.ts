import { IBooking } from './booking.interface';

export interface IBookingCollection extends IBooking {
  results: number;
  total_days: number;
  total_amount: number;

  g_results: number;
  g_total_days: number;
  g_total_amount: number;
}
