<?php namespace App\Repositories;

abstract class BaseRepository
{
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