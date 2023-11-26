import { ILicencePlate } from '../licence-plate/licence-plate.interface';
import { IVehicle } from '../vehicle/vehicle.interface';
import { IVisitDetails } from './visit-details.interface';
import { IVisit } from './visit.interface';

export interface IVisitCollection extends IVisit {
  results: number;
  g_results: number;
}
