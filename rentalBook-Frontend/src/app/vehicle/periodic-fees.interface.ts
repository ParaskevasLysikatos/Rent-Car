export interface IPeriodicFees {
  id: string,
  periodic_fee_type_id: string,
  vehicle_id: string,
  title: string,
  description: string,
  fee: string,
  date_start: string,
  date_expiration: string,
  date_payed: string,
}
