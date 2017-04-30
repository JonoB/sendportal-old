<?php

namespace App\Repositories;

use App\Interfaces\NewsletterRepositoryInterface;
use App\Models\Newsletter;

class NewsletterEloquentRepository extends BaseEloquentRepository implements NewsletterRepositoryInterface
{
    protected $modelName = Newsletter::class;
}
