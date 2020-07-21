<?php

namespace App\Services\Teams;

use App\Models\Team;
use App\Models\User;

class AddTeamMember
{
    /**
     * Attach a user to a team
     *
     * @param Team $team
     * @param User $user
     * @param string $role
     */
    public function handle(Team $team, User $user, $role = null)
    {
        if ( ! $user->onTeam($team, $user))
        {
            $team->users()->attach($user, ['role' => $role ?: Team::ROLE_MEMBER]);
        }
    }
}