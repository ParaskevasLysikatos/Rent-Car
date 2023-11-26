import { ICategories } from './categories.interface';

export interface ICategoriesCollection extends ICategories {
  results: number;
  g_results: number;
}
