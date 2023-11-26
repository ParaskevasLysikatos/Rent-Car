import { IBookingSource } from './booking-source.interface';

export interface IBookingSourceCollection extends IBookingSource {
  results: number;
  g_results: number;
}
