import { ITransition } from './transition.interface';

export interface ITransitionCollection extends ITransition {
  results: number;
  g_results: number;
}
