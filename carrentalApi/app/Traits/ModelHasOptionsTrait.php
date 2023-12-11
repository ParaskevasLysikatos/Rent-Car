<?php

namespace App\Traits;

use App\Option;
use App\OptionLink;
use Request;

trait ModelHasOptionsTrait {

    public static function bootModelHasOptionsTrait() {
        static::saved(function ($object) {
            // $object->addOptionsFromRequest(); //v1->v2 every model that has this trait, in controller on create method after save, should be invoked like $var->method();
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function options() {
        return $this->morphToMany(Option::class, 'option_link')->orderBy('ordering')->orderBy('id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function extras() {
        return $this->options()->where('option_type', 'extras');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function insurances() {
        return $this->options()->where('option_type', 'insurances');
    }

    /**
     * Associate document with current model
     *
     * @param \App\Option $option
     * @param bool       $is_main
     * @param int        $ordering
     *
     * @return \App\static:class model class
     */
    public function addOptionLink($option, int $ordering = 100): self
    {
        $option_link = OptionLink::where(['option_id' => $option->id, 'option_link_id' => $this->id, 'option_link_type' => static::class])->first();
        if (!$option_link) {
            $option_link                    = new OptionLink();
            $option_link->option_id         = $option->id;
            $option_link->ordering          = $ordering;
            $option_link->option_link_id    = $this->id;
            $option_link->option_link_type  = static::class;

            $option_link->save();
        }

        return $this;
    }

    public function addOptionsFromRequest() {
        if (Request::has('options')) {
            $this->options()->detach();
            $options = Request::input('options');
            if ($options) {
                $this->addOptions($options);
            }
        }
        if (Request::has('extras')) {
            $this->options()->detach($this->extras->pluck('id'));
            $extras = Request::input('extras');
            if ($extras) {
                $this->addOptions($extras);
            }
        }
        if (Request::has('insurances')) {
            $this->options()->detach($this->insurances->pluck('id'));
            $insurances = Request::input('insurances');
            if ($insurances) {
                $this->addOptions($insurances);
            }
        }
    }

    public function addOptions($option_ids) {
        foreach ($option_ids as $option_id) {
            $option = Option::find($option_id);
            $this->addOptionLink($option);
        }
    }
}