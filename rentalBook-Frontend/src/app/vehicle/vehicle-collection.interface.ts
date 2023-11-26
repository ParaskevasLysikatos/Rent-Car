import { IVehicle } from './vehicle.interface';
import { IStation } from '../stations/station.interface';

export interface IVehicleCollection extends IVehicle {
  station:IStation;
  results: number;
  g_results: number;
}
