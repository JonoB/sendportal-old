<?php

namespace App\Models;

class ConfigType extends BaseModel
{

    const AWS_SNS = 1;
    const SENDGRID = 2;
    const MAILGUN = 3;

    /**
     * @var array
     */
    protected $casts = [
        'fields' => 'array'
    ];
}
