import { ICharacteristics } from './characteristics.interface';

export interface ICharacteristicsCollection extends ICharacteristics {
  results: number;
  g_results: number;
}
