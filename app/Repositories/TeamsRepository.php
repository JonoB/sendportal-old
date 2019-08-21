<?php namespace App\Repositories;

use App\Models\Team;

class TeamsRepository extends BaseEloquentRepository
{
    protected $modelName = Team::class;
}
