<?php

namespace App\Models;

class UnsubscribeEventType extends BaseModel
{
    const BOUNCE = 1;
    const COMPLAINT = 2;
    const MANUAL_BY_ADMIN = 3;
    const MANUAL_BY_SUBSCRIBER = 4;

    /**
     * @var bool
     */
    public $timestamps = false;
}
