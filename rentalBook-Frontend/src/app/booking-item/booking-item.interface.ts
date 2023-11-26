import { IOptions } from "../options/options.interface";


export interface IBookingItem {
    id: string;
    created_at?: Date;
    updated_at?: Date;
    option: IOptions;
    option_id: string;
    daily: boolean;
    duration: number;
    quantity: number;
    payer: string;
    cost: number;
    net_total: number;
    total_cost: number;
    start: Date|null;
    end: Date|null;
}
