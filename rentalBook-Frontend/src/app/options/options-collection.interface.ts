import { IOptions } from './options.interface';

export interface IOptionsCollection extends IOptions {
  results_t: number;
  results_i: number;
  results_e: number;

  g_results_t: number;
  g_results_i: number;
  g_results_e: number;
}
