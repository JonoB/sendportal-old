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

/*
|--------------------------------------------------------------------------
| Form macros
|--------------------------------------------------------------------------
*/

Form::macro('textField', function ($name, $label = null, $value = null, $attributes = array())
{
    $element = Form::text($name, $value, Form::fieldAttributes($name, $attributes));

    return Form::fieldWrapper($name, $label, $element);
});

Form::macro('passwordField', function ($name, $label = null, $attributes = array())
{
    $element = Form::password($name, Form::fieldAttributes($name, $attributes));

    return Form::fieldWrapper($name, $label, $element);
});

Form::macro('textareaField', function ($name, $label = null, $value = null, $attributes = array())
{
    $element = Form::textarea($name, $value, Form::fieldAttributes($name, $attributes));

    return Form::fieldWrapper($name, $label, $element);
});

Form::macro('selectField', function ($name, $label = null, $options, $value = null, $attributes = array())
{
    $element = Form::select($name, $options, $value, Form::fieldAttributes($name, $attributes));

    return form::fieldWrapper($name, $label, $element);
});

Form::macro('selectMultipleField', function ($name, $label = null, $options, $value = null, $attributes = array())
{
    $attributes = array_merge($attributes, ['multiple' => true]);
    $element = Form::select($name, $options, $value, Form::fieldAttributes($name, $attributes));

    return Form::fieldWrapper($name, $label, $element);
});

Form::macro('selectRangeField', function ($name, $label = null, $begin, $end, $value = null, $attributes = array())
{
    $range = array_combine($range = range($begin, $end), $range);

    $element = Form::select($name, $range, $value, Form::fieldAttributes($name, $attributes));

    return form::fieldWrapper($name, $label, $element);
});

Form::macro('selectMonthField', function ($name, $label = null, $value = null, $attributes = array())
{
    $months = array();

    foreach (range(1, 12) as $month)
    {
        $months[$month] = strftime('%B', mktime(0, 0, 0, $month, 1));
    }

    $element = Form::select($name, $months, $value, Form::fieldAttributes($name, $attributes));

    return form::fieldWrapper($name, $label, $element);
});


Form::macro('checkboxField', function ($name, $label = null, $value = 1, $checked = null, $attributes = array())
{
    $attributes = array_merge(['id' => 'id-field-' . $name], $attributes);

    $out = '<div class="checkbox';
    $out .= Form::fieldError($name) . '">';
    $out .= '<label>';
    $out .= Form::checkbox($name, $value, $checked, $attributes) . ' ' . $label;
    $out .= '</div>';

    return $out;
});

Form::macro('submitButton', function ($label = 'Save', array $params = [])
{
    $defaults = [
        'class' => 'btn btn-primary'
    ];

    $attr = $params + $defaults;
    $res = [];

    foreach ($attr as $key => $val)
    {
        $res[] = e($key).'="'.e($val).'"';
    }

    return '<button type="submit" '.implode(' ', $res).'>' . $label . '</button>';
});

Form::macro('fieldWrapper', function ($name, $label, $element)
{
    $out = '<div class="form-group form-group-' . $name;
    $out .= Form::fieldError($name) . '">';
    $out .= Form::fieldLabel($name, $label);
    $out .= $element;
    $out .= '</div>';

    return $out;
});

Form::macro('fieldError', function ($field)
{
    $error = '';

    if ($errors = Session::get('errors'))
    {
        $error = $errors->first($field) ? ' has-error' : '';
    }

    return $error;
});

Form::macro('fieldErrorMessage', function ($field)
{
    $error = '';

    if ($errors = Session::get('errors'))
    {
        $error = $errors->first($field);
    }

    return $error;
});

Form::macro('fieldLabel', function ($name, $label)
{
    if (is_null($label)) return '';

    $name = str_replace('[]', '', $name);

    $out = '<label for="id-field-' . $name . '" class="control-label">';
    $out .= $label . '</label>';

    return $out;
});

Form::macro('fieldAttributes', function ($name, $attributes = array())
{
    $name = str_replace('[]', '', $name);

    $class = 'form-control';
    if (array_get($attributes, 'class'))
    {
        $class .= ' ' . array_get($attributes, 'class');
    }

    $attributes['class'] = $class;

    return array_merge(['id' => 'id-field-' . $name], $attributes);
});
