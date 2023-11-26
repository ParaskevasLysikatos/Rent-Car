<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'type_id' => $this->type_id,
            'user_id' => $this->user_id,
            'user'=>$this->user->name ?? null,
            'path' => $this->path,
            'public_path' => url('storage/'.$this->path),//extra
            'mime_type' => $this->mime_type,
            'md5' => $this->md5,
            'comments' => $this->comments,
            'name' => $this->name,
            'document_type' => $this->document_type,
            'documents' =>[//for itself web page
                'id' => $this->id,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,

                'type_id' => $this->type_id,
                'user_id' => $this->user_id,
                'path' => $this->path,
                'public_path' => url('storage/' . $this->path), //extra
                'mime_type' => $this->mime_type,
                'md5' => $this->md5,
                'comments' => $this->comments,
                'name' => $this->name,
                'document_type' => $this->document_type,
            ],

        ];
    }
}
