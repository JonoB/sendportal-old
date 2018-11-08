<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AutomationEmailContentController extends Controller
{
    public function edit($automationId, $emailId)
    {
        return redirect(route('automations.show', ['id' => $automationId]));
    }
}
