<?php

namespace App\Traits;

use App\Models\Invitation;
use App\Models\Team;
use InvalidArgumentException;

trait CanJoinTeams
{
    /**
     * Determine if the user is a member of any teams.
     *
     * @return bool
     */
    public function hasTeams()
    {
        return count($this->teams) > 0;
    }

    /**
     * Get all of the teams that the user belongs to.
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_users')
            ->orderBy('name', 'asc')
            ->withPivot(['role'])
            ->withTimestamps();
    }

    /**
     * Get all of the pending invitations for the user.
     */
    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    /**
     * Determine if the user is on the given team.
     *
     * @param Team $team
     *
     * @return bool
     */
    public function onTeam($team)
    {
        return $this->teams->contains($team);
    }

    /**
     * Determine if the given team is owned by the user.
     *
     * @param Team $team
     *
     * @return bool
     */
    public function ownsTeam($team)
    {
        return $this->id && $team->owner_id && $this->id == $team->owner_id;
    }

    /**
     * Get all of the teams that the user owns.
     */
    public function ownedTeams()
    {
        return $this->hasMany(Team::class, 'owner_id');
    }

    /**
     * Get the user's role on a given team.
     *
     * @param Team $team
     *
     * @return string
     */
    public function roleOn($team)
    {
        if ($team = $this->teams->find($team->id))
        {
            return $team->pivot->role;
        }
    }

    /**
     * Get the user's role on the team currently being viewed.
     *
     * @return string
     */
    public function roleOnCurrentTeam()
    {
        return $this->roleOn($this->currentTeam);
    }

    /**
     * Accessor for the currentTeam method.
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function getCurrentTeamAttribute()
    {
        return $this->currentTeam();
    }

    /**
     * Get the team that user is currently viewing.
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function currentTeam()
    {
        if (is_null($this->current_team_id) && $this->hasTeams())
        {
            $this->switchToTeam($this->teams->first());

            return $this->currentTeam();
        }
        elseif ( ! is_null($this->current_team_id))
        {
            $currentTeam = $this->teams->find($this->current_team_id);

            return $currentTeam ?: $this->refreshCurrentTeam();
        }
    }

    /**
     * Determine if the current team is on a trial.
     *
     * @return bool
     */
    public function currentTeamOnTrial()
    {
        return $this->currentTeam() && $this->currentTeam()->onTrial();
    }

    /**
     * Determine if the user owns the current team.
     *
     * @return bool
     */
    public function ownsCurrentTeam()
    {
        return $this->currentTeam() && $this->currentTeam()->owner_id == $this->id;
    }

    /**
     * Switch the current team for the user.
     *
     * @param Team $team
     *
     * @return void
     */
    public function switchToTeam($team)
    {
        if ( ! $this->onTeam($team))
        {
            throw new InvalidArgumentException('User does not belong to this team');
        }

        $this->current_team_id = $team->id;

        $this->save();
    }

    /**
     * Refresh the current team for the user.
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function refreshCurrentTeam()
    {
        $this->current_team_id = null;

        $this->save();

        return $this->currentTeam();
    }

    /**
     * Get the total number of potential collaborators across all teams.
     *
     * This does not include the current user instance.
     *
     * @return int
     */
    public function totalPotentialCollaborators()
    {
        return $this->ownedTeams->sum(function ($team)
            {
                return $team->totalPotentialUsers();
            }) - $this->ownedTeams->count();
    }
}
