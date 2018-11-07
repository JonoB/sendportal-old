<?php

namespace App\Http\Controllers;

use Illuminate\Support\ViewErrorBag;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Helper for add custom error messages to message bag.
     *
     * @param string $key
     * @param string $value
     * @param string $bag_name
     */
    protected function addToErrorsBag($key, $value, $bag_name = 'default')
    {
        $viewErrorBag = session()->get('errors', new ViewErrorBag);

        $errorBag = $viewErrorBag->getBag($bag_name);

        $errorBag->add($key, $value);

        $viewErrorBag->put($bag_name, $errorBag);

        return $viewErrorBag;
    }
}
