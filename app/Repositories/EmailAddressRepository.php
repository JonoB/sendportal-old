<?php

namespace App\Repositories;

use App\Interfaces\EmailAddressRepositoryInterface;
use App\Models\EmailAddress;

class EmailAddressRepository extends BaseEloquentRepository implements EmailAddressRepositoryInterface
{
    /**
     * @var string
     */
    protected $modelName = EmailAddress::class;
}
