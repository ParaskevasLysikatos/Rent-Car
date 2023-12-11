<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TypeResource extends JsonResource
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
            'slug' => $this->slug,
            'category_id' => $this->category_id,
            'category' => $this->category ? new CategoryResource($this->category) : null,
            'options' => new OptionCollection($this->options),
            'optionsCount' => count(new OptionCollection($this->options)),
            'characteristics' => new CharacteristicsCollection($this->characteristics),
            'characteristicsCount' => count(new CharacteristicsCollection($this->characteristics)),
            'min_category' => $this->min_category,
            'max_category' => $this->max_category,
            'international_code' => $this->international_code,
            'excess' => $this->excess,
            'icon' => $this->icon ? url('storage/' . $this->icon) : null,
            'profiles' => ProfileResource::collection($this->profiles),
            'images' => ImageResource::collection($this->images),
            'imagesCount' => count(new ImageCollection($this->images)),
            'IamGroup'=>''
        ];
    }
}
