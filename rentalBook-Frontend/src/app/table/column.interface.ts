import { TemplateRef } from "@angular/core";


export interface IColumn {
    columnDef: string;
    header: string;
    cell?: (element: any) => any;
    cellTemplate?: TemplateRef<any>;
    visible?: boolean;
    sortBy?: any;
    hasFilter?: boolean;
    filterField?: string;
    filterTemplate?: TemplateRef<any>;
  filterTemplateGeneral?: TemplateRef<any>;
  filterValue?: (value:any) => string
}
