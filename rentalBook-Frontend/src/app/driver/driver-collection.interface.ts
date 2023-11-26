import { IDriver } from './driver.interface';

export interface IDriverCollection extends IDriver {
  results: number;
  g_results: number;
}
