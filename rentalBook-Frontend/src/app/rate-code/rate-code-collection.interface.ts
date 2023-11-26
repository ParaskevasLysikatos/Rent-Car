import { IRateCode } from './rate-code.interface';

export interface IRateCodeCollection extends IRateCode {
  results: number;
  g_results: number;
}
