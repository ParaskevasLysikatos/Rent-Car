<?php

namespace App\Http\Resources;

use App\Models\Characteristic;
use App\Models\Category;
use App\Models\Option;
use Illuminate\Http\Resources\Json\JsonResource;

class CombinedResourceTypes extends JsonResource
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
            'characteristics' => CharacteristicsResource::collection(Characteristic::all()),
            'categories' => CategoryResource::collection(Category::all()),
            'options' => OptionResource::collection(Option::all()),
        ];
    }
}
