import { IStation } from "../stations/station.interface";
import { IPlace } from "./place.interface";

export interface IPlaceCollection extends IPlace {
    stations: IStation[];
  results: number;
  g_results: number;
}
