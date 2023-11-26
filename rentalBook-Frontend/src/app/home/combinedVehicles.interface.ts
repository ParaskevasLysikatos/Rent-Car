import { IClassType } from "../class-type/class-type.interface";
import { IColorType } from "../color-type/color-type.interface";
import { IDriveType } from "../drive-type/drive-type.interface";
import { IFuelType } from "../fuel-type/fuel-type.interface";
import { IOwnershipType } from "../ownership-type/ownership-type.interface";
import { IPeriodicFeeTypes } from "../periodic-fee-types/periodic-fee-types.interface";
import { IStation } from "../stations/station.interface";
import { ITransmissionType } from "../transmission-type/transmission-type.interface";
import { ITypes } from "../types/types.interface";
import { IUseType } from "../use-type/use-type.interface";
import { IVehicleStatus } from "../vehicle-status/vehicle-status.interface";

export interface ICombinedVehicles {

  stations: IStation[];
  groups: ITypes[];

  class: IClassType[];
  fuel: IFuelType[];
  ownership: IOwnershipType[];
  use: IUseType[];
  periodicFee_types: IPeriodicFeeTypes[];
  transmission: ITransmissionType[];
  drive_type: IDriveType[];
  color_type: IColorType[];
  vehicleStatus: IVehicleStatus[];

}
