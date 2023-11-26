<?php

namespace App\Traits;

use App\Tag;
use App\TagLink;
use Request;

trait ModelHasTagsTrait {

    public static function bootModelHasTagsTrait() {
        static::saved(function ($object) {
           // $object->addTags(); //v1->v2 every model that has this trait, in controller on create method after save, should be invoked like $var->method();
        });
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'tag_link');
    }

    /**
     * Associate tag with current model
     *
     * @param \App\Tag $document
     *
     * @return \App\static:class model class
     */
    public function addTagLink(Tag $tag)
    {
       $tag_link = TagLink::where(['tag_id' => $tag->id, 'tag_link_id' => $this->id, 'tag_link_type' => static::class])->first();

        if (!$tag_link) {
            $tag_link                 = new TagLink();
            $tag_link->tag_link_id    = $this->id;
            $tag_link->tag_link_type  = static::class;
            $tag_link->tag_id         = $tag->id;

            $tag_link->save();
        }

        return $this;
    }

    public function addTags()
    {
        if (Request::has('tags')) {
            $this->tags()->detach();
            $tags = Request::get('tags');
            if ($tags) {
                foreach ($tags as $tag) {
                    $tag = Tag::firstOrCreate(['title' => $tag]);
                    $this->addTagLink($tag);
                }
            }
        }
    }


    public function addTags2() {
        if (Request::has('tags')) {
            $this->tags()->detach();
            $tags = Request::get('tags');
            if ($tags) {
                foreach ($tags as $tag) {
                    if($tag['title']){
                    $tag = Tag::firstOrCreate(['title' => $tag['title']]);
                    }
                    else {
                        $tag = Tag::firstOrCreate(['title' => $tag]);
                    }
                    $this->addTagLink($tag);
                }
            }
        }
    }


}