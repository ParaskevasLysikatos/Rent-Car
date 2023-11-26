import { IAgent } from "../agent/agent.interface";
import { IBrand } from "../brand/brand.interface";
import { IProgram } from "../program/program.interface";

export interface ICombinedBookingSources {

  agent: IAgent[];
  programs: IProgram[];
  brands: IBrand[];
}
