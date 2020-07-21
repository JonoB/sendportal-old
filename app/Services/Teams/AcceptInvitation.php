<?php

namespace App\Services\Teams;

use App\Models\Invitation;
use App\Models\Team;
use App\Models\User;

class AcceptInvitation
{
    /**
     * @var AddTeamMember
     */
    protected $addTeamMember;

    /**
     * CreateTeamService constructor
     *
     * @param AddTeamMember $addTeamMember
     */
    public function __construct(AddTeamMember $addTeamMember)
    {
        $this->addTeamMember = $addTeamMember;
    }

    /**
     * Accept user invitation
     *
     * @param User $user
     * @param Invitation $invitation
     * @return bool
     * @throws \Exception
     */
    public function handle(User $user, Invitation $invitation)
    {
        $team = $this->resolveTeam($invitation->team_id);

        $this->addTeamMember->handle($team, $user, Team::ROLE_MEMBER);

        $invitation->delete();

        return true;
    }

    /**
     * Resolve the team
     *
     * @param int $teamId
     * @return mixed
     */
    protected function resolveTeam($teamId)
    {
        return Team::where('id', $teamId)->first();
    }
}