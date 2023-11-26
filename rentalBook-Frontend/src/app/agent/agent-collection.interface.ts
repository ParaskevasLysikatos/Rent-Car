import { IBookingSource } from '../booking-source/booking-source.interface';
import { ICompany } from '../company/company.interface';
import { IAgent } from './agent.interface';

export interface IAgentCollection extends IAgent {
    booking_source: IBookingSource;
    company: ICompany;
    parent_agent: IAgent;
    // program: IProgram;
  results: number;
  g_results: number;
}
