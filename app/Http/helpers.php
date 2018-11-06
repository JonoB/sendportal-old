<?php

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
            return round($value / 1000000) . 'k';
        }

        return $result = $value;
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
