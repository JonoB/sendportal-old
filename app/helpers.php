<?php

use App\Models\User;
use Carbon\Carbon;

/**
 * Get active user
 *
 * @return \Illuminate\Contracts\Auth\Authenticatable|null
 */
function user()
{
    return auth()->user();
}

/**
 * Get the current team
 *
 * @return mixed
 */
function currentTeam()
{
    if ($user = user())
    {
        return $user->currentTeam;
    }

    return null;
}

/**
 * Get the current team ID
 *
 * @return mixed
 */
function currentTeamId()
{
    if ($teamId = config('current_team_id'))
    {
        return $teamId;
    }

    elseif (currentTeam())
    {
        return currentTeam()->id;
    }

    return null;
}

/**
 * Display a given date in the active user's timezone.
 *
 * @param $date
 * @param string $timezone
 * @return Carbon
 */
function displayDate($date, string $timezone = null): Carbon
{
    if ( ! $timezone)
    {
        $timezone = user()->timezone;
    }

    return Carbon::parse($date)->copy()->setTimezone($timezone);
}

/**
 * Return a date adjusted for a timezone
 *
 * @param Carbon $date
 * @param string $timezone
 * @return Carbon
 */
function timezoneDate(Carbon $date, string $timezone): Carbon
{
    $offset = getTimezoneOffset($date, $timezone);

    return $date->copy()->subSeconds($offset);
}

/**
 * Return the start of day adjusted for a timezone
 *
 * @param $date
 * @param $timezone
 * @return Carbon
 */
function timezoneStartOfDay($date, $timezone): Carbon
{
    $start = startOfDay($date);

    return timezoneDate($start, $timezone);
}

/**
 * Return the start of day adjusted for a timezone
 *
 * @param $date
 * @param $timezone
 * @return Carbon
 */
function timezoneEndOfDay($date, $timezone): Carbon
{
    $start = endOfDay($date);

    return timezoneDate($start, $timezone);
}

/**
 * Return timezone offset as absolute integer in seconds
 *
 * @param $date
 * @param null $tz
 * @return int
 */
function getTimezoneOffset($date, $tz): int
{
    return Carbon::parse($date)->copy()->timezone($tz)->format('Z');
}

/**
 * Return the start of the day
 *
 * @param $date
 * @return Carbon
 */
function startOfDay($date): Carbon
{
    return Carbon::parse($date)->copy()->startOfDay();
}

/**
 * Return the end of the day
 *
 * @param $date
 * @return Carbon
 */
function endOfDay($date): Carbon
{
    return Carbon::parse($date)->copy()->endOfDay();
}

if ( ! function_exists('assetUrl'))
{

    /**
     * Create a timestamped asset url for cache busting
     *
     * @param string $path
     * @param null $secure
     * @return string
     */
    function assetUrl($path, $secure = null)
    {
        $filePath = public_path($path);
        $modifier = File::lastModified($filePath);
        $url = asset($path, $secure);

        return $url . '?m=' . $modifier;
    }
}

if ( ! function_exists('selectedOptions'))
{
    /**
     * Get an array of values from a relation
     *
     * @param string $relation
     * @param mixed $object
     * @param string $key
     * @return array
     */
    function selectedOptions($relation, $object = null, $key = 'id')
    {
        $result = [];

        if (request()->old($relation))
        {
            foreach (request()->old($relation) as $item)
            {
                $result[] = $item;
            }
        }
        elseif (isset($object->$relation))
        {
            foreach ($object->$relation as $item)
            {
                $result[] = $item->$key;
            }
        }

        return $result;
    }
}

if ( ! function_exists('formatValue'))
{
    /**
     * Format a value to return short notation
     *
     * @param float $value
     * @return string
     */
    function formatValue($value)
    {
        if ($value > 9999 && $value <= 999999)
        {
            return round($value / 1000) . 'k';
        }

        if ($value > 999999)
        {
            return round($value / 1000000) . 'm';
        }

        return $value;
    }
}

if ( ! function_exists('formatRatio'))
{
    /**
     * Format a ratio to percentage with decimals
     *
     * @param float $value
     * @param int $decimals
     * @return string
     */
    function formatRatio($value, $decimals = 1)
    {
        return number_format($value * 100, $decimals) . '%';
    }
}

/**
 * Convert an array to a CSV download
 */
if ( ! function_exists('csvFromArray'))
{
    /**
     * @param array       $data
     * @param array $whitelist
     * @param string $delim
     * @param string $newline
     * @param string $enclosure
     * @return array|string
     */
    function csvFromArray($data, $whitelist = [], $delim = ",", $newline = "\n", $enclosure = '"')
    {
        if (empty($data))
        {
            return '';
        }

        $out = '';
        $dbFields = [];

        // if a whitelist has been setup, then grab that for the column headings
        if ( ! empty($whitelist) && is_array($whitelist))
        {
            $headings = $whitelist;
            $dbFields = array_keys($whitelist);
        }

        // otherwise use the table column names
        else
        {
            $headings = array_keys((array)$data[0]);
        }

        // Generate the text csv headings
        foreach ($headings as $heading)
        {
            $out .= $enclosure.str_replace($enclosure, $enclosure.$enclosure, $heading).$enclosure.$delim;
        }

        $out = rtrim($out, $delim);
        $out .= $newline;

        // Next blast through the result array and build out the rows
        foreach ($data as $row)
        {
            // force the row to be an object
            $row = json_decode(json_encode($row));

            // if we are using a whitelist, then return the
            // items in the order of the whitelist
            if ($whitelist)
            {
                foreach ($dbFields as $dbField)
                {
                    if (property_exists($row, $dbField))
                    {
                        $out .= $enclosure.str_replace($enclosure, $enclosure.$enclosure, $row->$dbField).$enclosure.$delim;
                    }
                }
            }
            else
            {
                foreach ($row as $item)
                {
                    $out .= $enclosure.str_replace($enclosure, $enclosure.$enclosure, $item).$enclosure.$delim;
                }
            }

            $out = rtrim($out, $delim);
            $out .= $newline;
        }

        return $out;
    }
}

if ( ! function_exists('normalize_tags'))
{
    /**
     * Normalize a tag
     *
     * @param string $content
     * @param string $tag
     * @return string
     */
    function normalize_tags(string $content, string $tag)
    {
        $search = [
            '{{ ' . $tag . ' }}',
            '{{' . $tag . ' }}',
            '{{ ' . $tag . '}}',
        ];

        return str_ireplace($search, '{{' . $tag . '}}', $content);
    }
}