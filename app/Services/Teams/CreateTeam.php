<?php

namespace App\Services\Teams;

use App\Repositories\TeamsRepository;
use Illuminate\Support\Carbon;

class CreateTeam
{
    /**
     * @var TeamsRepository
     */
    protected $teamsRepo;

    /**
     * @var AddTeamMember
     */
    protected $addTeamMember;

    /**
     * @var TemplatePersistenceService
     */
    protected $templates;

    /**
     * CreateTeamService constructor
     *
     * @param TeamsRepository $teamsRepo
     * @param AddTeamMember $addTeamMember
     */
    public function __construct(TeamsRepository $teamsRepo, AddTeamMember $addTeamMember)
    {
        $this->teamsRepo = $teamsRepo;
        $this->addTeamMember = $addTeamMember;
    }

    /**
     * Create a new team.
     *
     * @param $user
     * @param array $data
     * @param null $role
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle($user, array $data, $role = null)
    {
        // @todo wrap in db transaction

        $team = $this->teamsRepo->store([
            'name' => $data['company_name'],
            'owner_id' => $user->id,
            'trial_ends_at' => $this->getTrialExpiryDate()->timestamp,
        ]);

        $this->addTeamMember->handle($team, $user, $role);

        return $team;
    }

    /**
     * Get the trial expiry date for a newly created team.
     *
     * @param null $now
     *
     * @return Carbon
     */
    protected function getTrialExpiryDate($now = null)
    {
        $now = $now ?: now();
        $trialDays = config('subscriptions.trial_period');

        return $now->addDays($trialDays);
    }
}