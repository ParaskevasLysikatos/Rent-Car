<?php

namespace App\Traits;

use App\Models\Image;
use App\Models\ImageLink;
use Request;
use Str;

trait ModelHasImagesTrait {

    public static function bootModelHasImagesTrait() {
        static::saved(function ($object) {
          //  $object->addImages(); //v1->v2 every model that has this trait, in controller on create method after save, should be invoked like $var->method();
        });
    }

    public abstract function getImagesPath();
    public abstract function getInitialFileName();

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function images() {
        return $this->morphToMany(Image::class, 'image_link')->orderBy('ordering')->orderBy('id');
    }

    /**
     * Associate document with current model
     *
     * @param \App\Image $image
     * @param bool       $is_main
     * @param int        $ordering
     *
     * @return \App\static:class model class
     */
    public function addImageLink(Image $image, bool $is_main = FALSE, int $ordering = 100): self
    {
        $image_link = ImageLink::where(['image_id' => $image->id, 'image_link_id' => $this->id, 'image_link_type' => static::class])->first();

        if (!$image_link) {
            $image_link                  = new ImageLink();
            $image_link->image_id        = $image->id;
            $image_link->is_main         = $is_main;
            $image_link->ordering        = $ordering;
            $image_link->image_link_id   = $this->id;
            $image_link->image_link_type = static::class;

            $image_link->save();
        }

        return $this;
    }

    public function addImage($image) {
        $path = $this->getImagesPath();
        $initialFileName = $this->getInitialFileName();
        $fileName = $initialFileName."-".$this->id."-".Str::random(8).time().".".$image->getClientOriginalExtension();
        $image->storeAs('public/'.$path, $fileName);

        $newIMG            = new Image();
        $newIMG->path      = $path.$fileName;
        $newIMG->mime_type = $image->getMimeType();
        $newIMG->save();

        $this->addImageLink($newIMG);
    }

    public function addImages() {
        $images = Request::file('images');
        if (Request::hasFile('images')) {
            foreach ($images as $image) {
                $this->addImage($image);
            }
        }
    }
}
