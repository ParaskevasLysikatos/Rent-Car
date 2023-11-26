import { IUser } from './user.interface';

export interface IUserCollection extends IUser {
  results: number;
  g_results: number;
}
