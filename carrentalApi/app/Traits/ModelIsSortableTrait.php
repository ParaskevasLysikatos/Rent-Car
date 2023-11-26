<?php


namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Kirschbaum\PowerJoins\PowerJoins;

trait ModelIsSortableTrait
{
    use PowerJoins;

    public function applyOrder(Builder $builder, Request $request)
    {
        if ($request->has('orderBy')) {
            $order = 'ASC';
            if ($request->has('orderByType')) {
                $order = $request->orderByType;
            }
            $orderBy = $request->orderBy;
            $orderByStr = str_replace(' ', '', ucwords(str_replace('_', ' ', $orderBy)));
            if (method_exists(self::class, 'orderBy'. $orderByStr)) {
                $this->{'orderBy'.$orderByStr}($builder, $order);
            } else if (strpos($orderBy, '.') !== false) {
                $builder->orderByPowerJoins($orderBy, $order, null, 'leftJoin');
            } else {
                $builder->orderBy($orderBy, $order);
            }
        }

        return $builder;
    }

    public function scopeApplyOrder(Builder $builder, Request $request)
    {
        return $this->applyOrder($builder, $request);
    }
}
