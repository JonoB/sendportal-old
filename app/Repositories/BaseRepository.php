<?php namespace App\Repositories;

abstract class BaseRepository
{

    /**
     * Validation errors
     *
     * @var $validationErrors
     */
    protected $validationErrors;

    /**
     * Return current active user id
     *
     * @return int|bool
     */
    public function getActiveUserId()
    {
        // first check if user is "logged in" through api
        if ($apiUserId = \Config::get('api_user_id'))
        {
            return $apiUserId;
        }
        // Next check if user is logged in through sentry
        if ($user = $this->getActiveUser())
        {
            return $user->id;
        }
        return false;
    }

    /**
     * Return active user model
     *
     * @return \App\User
     */
    public function getActiveUser()
    {
        return \App\User::getUser();
    }

    /**
     * Check if a table is joined
     * https://gist.github.com/goranprijic/c578acb0086e8cd85179
     *
     * @param object $query
     * @param string $table
     * @return bool
     */
    public static function isJoined($query, $table)
    {
        $joins = $query->getQuery()->joins;
        if ($joins == null)
        {
            return false;
        }

        foreach ($joins as $join)
        {
            if ($join->table == $table)
            {
                return true;
            }
        }

        return false;
    }
}