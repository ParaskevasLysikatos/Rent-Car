<?php

use Carbon\Carbon;

function formatDate($date, $format = '')
{
    if (!$format) $format = config('ea.php_date_format');
    if (!$date) return "";
    return date($format, strtotime($date));
}

function formatDatetime($date, $format = '')
{
    if (!$format) $format = config('ea.php_datetime_format');
    if (!$date) return "";
    return date($format, strtotime($date));
}

function formatTime($date, $format = '')
{
    if (!$format) $format = config('ea.php_time_format');
    if (!$date) return "";
    return date($format, strtotime($date));
}

function createSlug($str, $model)
{
    $model->slug = Illuminate\Support\Str::slug($str);
    $latestSlug = $model::whereRaw("slug = '$model->slug' or slug LIKE '$model->slug-%'");

    if (Schema::hasColumn($model->getTable(), 'deleted_at')) {
        $latestSlug = $latestSlug->withTrashed();
    }

    $latestSlug = $latestSlug->latest('id')->value('slug');
    if ($latestSlug) {
        $pieces = explode('-', $latestSlug);

        $number = intval(end($pieces));

        $model->slug .= '-' . ($number + 1);
    }
}

function duration_in_days(string $from, string $to, int $minutes_offset = 0)
{
    $from = Carbon::parse($from);
    $to = Carbon::parse($to);
    $days = $from->diffInDays($to);
    $to = $to->subDays($days);
    if ($from->diffInMinutes($to) > $minutes_offset) {
        $days++;
    }
    return $days;
}

function set_th_data(string $value)
{
    $orderType = (isset($_GET["orderBy"]) && $_GET["orderBy"] == $value && isset($_GET["orderByType"]) && $_GET["orderByType"] == 'ASC') ? 'DESC' : 'ASC';
    echo "data-orderBy=\"$value\"";
    echo "data-orderByType=\"$orderType\"";
}