<?php

namespace App\Filters;

use App\Agent;
use App\Company;
use App\Filters\InFilter;
use App\Filters\EqualFilter;
use Illuminate\Http\Request;
use App\Filters\AbstractFilter;
use App\Filters\DatetimeFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class InvoiceFilter extends AbstractFilter
{
    protected $filters = [
        'date' => DatetimeFilter::class,
        'station_id' => InFilter::class,
        'invoicee_id' => 'invoice',
        'type' => InFilter::class,
        'sent_to_aade' => 'aade',

        'date_to' => 'date_to_filter',
        'date_from' => 'date_from_filter',
        'sequence_number' => LikeFilter::class,
        'invoicee_id_find' => 'invoicee_id_filter',
    ];

    protected $extra_filters = [];

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->filters = array_merge($this->filters, $this->extra_filters);
    }

    public function aade(Builder $builder, $filter, $value) {
        $builder->where(function ($q) use ($filter, $value) {

            $q->where($filter, $value);
            if ($value === '0') {
                $q->orWhereNull($filter);
            }
        });

        return $builder;
    }

    public function invoice(Builder $builder, $filter, $value) {
        $has_invoicee_type = $this->request->has('invoicee_type');
        if ($value && $has_invoicee_type) {
            $invoicee_type = $this->request->invoicee_type;
            $builder->where(function (Builder $q) use($value, $invoicee_type) {
                $q->where('invoicee_id', $value)->where('invoicee_type', $invoicee_type);
                if ($invoicee_type == Agent::class) {
                    $q->orWhere('invoicee_id', Agent::find($value)->company->id)->where('invoicee_type', Company::class);
                } else if ($invoicee_type == Company::class) {
                    $agent = Company::find($value)->agent;
                    if ($agent) {
                        $q->orWhere('invoicee_id', $agent->id)->where('invoicee_type', Agent::class);
                    }
                }
            });
        }
        return $builder;
    }


    public function date_to_filter($builder, $filter, $value)
    {
        $to = Carbon::parse($value)->addDay(1)->format('Y-m-d');
        //echo $to;
        $builder = $builder->where('date', '<=', $to);
    }

    public function date_from_filter($builder, $filter, $value)
    {
        $from = Carbon::parse($value)->format('Y-m-d');
        // echo $from;
        $builder = $builder->where('date', '>=', $from);
    }

    public function invoicee_id_filter($builder, $filter, $value)
    {
        $builder = $builder->where('invoicee_id', 'like', '%' . $value . '%');

        return $builder;
    }

}
