<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AgentResource extends JsonResource
{
    public static $wrap = null;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'commission' => $this->commission,

            'booking_source_id' =>  new SourceResource($this->booking_source) ?? $this->booking_source_id,
            'booking_source'=> new SourceResource($this->booking_source) ?? null,

            'program_id' => $this->program_id,
            'program'=> new ProgramResource($this->program),

            'company_id' =>  new CompanyResource($this->company) ?? $this->company_id,
            'sub_agents' => $this->agents->pluck('id'),
            'comments' => $this->comments,
            'brand_id' =>  new BrandResource($this->brand) ?? $this->brand_id,

            'contact_information_id' => $this->contact_information_id,
            'contact_information'=>$this->contact_information,

            //'con_if_email'=>$this->contact_informationEmail(),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // 'parent' => $this->parent_agent_id ? new AgentResource($this->parent_agent) : null,
            'sub_contacts'=>$this->sub_contacts->pluck('id'),
            'sub_agents_details'=>$this->agents->pluck('name','commission'),

            'documents' => DocumentResource::collection($this->documents),
            'IamAgent'=>''
        ];
    }
}