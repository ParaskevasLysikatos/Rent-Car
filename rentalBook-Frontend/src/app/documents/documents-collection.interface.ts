import { IDocuments } from './documents.interface';

export interface IDocumentsCollection extends IDocuments {
  results: number;
  g_results: number;
}
