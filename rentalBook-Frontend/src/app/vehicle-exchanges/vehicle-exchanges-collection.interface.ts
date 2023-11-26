import { IVehicleExchanges } from './vehicle-exchanges.interface';

export interface IVehicleExchangesCollection extends IVehicleExchanges {
  results: number;
  g_results: number;
}
