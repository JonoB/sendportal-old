<?php

namespace App\Models;

class ProviderType extends BaseModel
{

    const AWS_SNS = 1;
    const SENDGRID = 2;
    const MAILGUN = 3;
    const POSTMARK = 4;

    /**
     * @var array
     */
    protected $casts = [
        'fields' => 'array'
    ];
}
