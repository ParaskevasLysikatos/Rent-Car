import { IPayment } from './payment.interface';

export interface IPaymentCollection extends IPayment {
    total_payment: number;
    total_refund: number;
    total_pre_auth: number;
    total_refund_pre_auth: number;

  total_results_p: number;
  total_results_r: number;
  total_results_pa: number;
  total_results_rpa: number;

  g_total_results_p: number;
  g_total_payment: number;

  g_total_results_r: number;
  g_total_refund: number;

  g_total_results_pa: number;
  g_total_pre_auth: number;

  g_total_results_rpa: number;
  g_total_refund_pre_auth: number;
}
