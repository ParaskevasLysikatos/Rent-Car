import { IRoles } from './roles.interface';

export interface IRolesCollection extends IRoles {
  results: number;
  g_results: number;
}
