<?php

namespace App\Filters;

use Illuminate\Http\Request;

class ReservationFilter extends AbstractFilter
{
    protected $filters = [
        'checkout_datetime' => DatetimeFilter::class,
        'checkin_datetime' => DatetimeFilter::class,
        'checkout_station_id' => InFilter::class,
        'checkin_station_id' => InFilter::class,
        'status' => InFilter::class,
        'agent_id' => InFilter::class,
        'source_id' => InFilter::class,
        'sub_account_id' => EqualFilter::class,
        'sub_account_type' => EqualFilter::class
    ];

    protected $extra_filters = [];

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->filters = array_merge($this->filters, $this->extra_filters);
    }
}
