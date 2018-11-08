<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CampaignEmailContentController extends Controller
{
    public function edit($campaignId, $emailId)
    {
        return redirect(route('campaigns.index'));
    }
}
