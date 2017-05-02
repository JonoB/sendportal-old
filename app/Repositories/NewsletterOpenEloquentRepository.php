<?php

namespace App\Repositories;

use App\Interfaces\NewsletterOpenRepositoryInterface;
use App\Models\NewsletterOpen;

class NewsletterOpenEloquentRepository extends BaseEloquentRepository implements NewsletterOpenRepositoryInterface
{
    protected $modelName = NewsletterOpen::class;
}
