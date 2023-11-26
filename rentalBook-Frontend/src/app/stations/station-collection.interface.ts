import { ILocation } from "../locations/location.inteface";
import { IStation } from "./station.interface";

export interface IStationCollection extends IStation {
    location: ILocation;
  results: number;
  g_results: number;
}
