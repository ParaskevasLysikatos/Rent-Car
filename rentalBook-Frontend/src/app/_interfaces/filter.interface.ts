import { TemplateRef } from "@angular/core";

export interface IFilter {
    label: string;
    field: string;
    valueFunction: (value: any) => string;
    value?: any | any[];
    templateRef?: TemplateRef<any>;
}
