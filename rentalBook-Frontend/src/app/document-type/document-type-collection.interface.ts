import { IDocumentType } from './document-type.interface';

export interface IDocumentTypeCollection extends IDocumentType {
  results: number;
  g_results: number;
}
