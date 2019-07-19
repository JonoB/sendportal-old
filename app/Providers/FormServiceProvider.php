<?php

namespace App\Providers;

use Form;
use Session;

use Illuminate\Support\ServiceProvider;

class FormServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
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

        Form::macro('fileField', function ($name, $label = null, $attributes = array())
        {
            $element = Form::file($name, Form::fieldAttributes($name, $attributes));

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

            $out = '<div class="form-group">';
            $out .= '<div class="col-sm-offset-2 col-sm-10">';
            $out .= '<button type="submit" '.implode(' ', $res).'>' . $label . '</button>';
            $out .= '</div>';
            $out .= '</div>';

            return $out;
        });

        Form::macro('fieldWrapper', function ($name, $label, $element)
        {
            $out = '<div class="form-group row form-group-' . $name;
            $out .= Form::fieldError($name) . '">';
            $out .= Form::fieldLabel($name, $label);
            $out .= '<div class="col-sm-10">';
            $out .= $element;
            $out .= '</div>';
            $out .= '</div>';

            return $out;
        });

        Form::macro('fieldError', function ($field)
        {
            $error = '';

            if ($errors = session('errors'))
            {
                $error = $errors->first($field) ? ' has-error' : '';
            }

            return $error;
        });

        Form::macro('fieldErrorMessage', function ($field)
        {
            $error = '';

            if ($errors = session('errors'))
            {
                $error = $errors->first($field);
            }

            return $error;
        });

        Form::macro('fieldLabel', function ($name, $label)
        {
            if (is_null($label)) return '';

            $name = str_replace('[]', '', $name);

            $out = '<label for="id-field-' . $name . '" class="control-label col-sm-2">';
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
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
