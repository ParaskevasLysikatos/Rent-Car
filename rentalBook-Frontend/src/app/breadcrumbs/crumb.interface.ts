import { Params } from "@angular/router";

export interface ICrumb {
    link: string;
    label: string;
    queryParams?: Params;
}