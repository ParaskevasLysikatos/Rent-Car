import { Observable } from 'rxjs';
import { GetParams } from './get-params.interface';
import { IPreview } from './preview.interface';

export interface IApiService<Type> {
    get: (filters?: GetParams, page?: number, pageSize?: number) => Observable<IPreview<Type>>;
    edit: (id: string) => Observable<Type>;
    create: (data: Type) => Observable<Type>;
    update: (id: string, data: Type) => Observable<Type>;
    delete: (id: string) => Observable<any>;
}
