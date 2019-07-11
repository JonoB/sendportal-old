<?php

namespace App\Models;

class Delivery extends BaseModel
{
    protected $guarded = [];

    // we can't use boolean fields on this model because
    // we have multiple points to update from the controller
    protected $booleanFields = [];
}
