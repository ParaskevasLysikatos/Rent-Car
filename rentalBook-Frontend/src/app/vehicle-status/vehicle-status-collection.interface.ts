import { IVehicleStatus } from './vehicle-status.interface';

export interface IVehicleStatusCollection extends IVehicleStatus {
  results: number;
  g_results: number;
}
