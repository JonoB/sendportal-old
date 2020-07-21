<?php

namespace App\Services\Teams;

use App\Models\Invitation;
use App\Models\Team;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class SendInvitation
{
    /**
     * Send invitation to a new team member
     *
     * @param Team $team
     * @param string $email
     * @return mixed
     * @throws \Exception
     */
    public function handle(Team $team, $email)
    {
        $existingUser = User::where('email', $email)->first();

        $invitation = $this->createInvitation($team, $email, $existingUser, TEAM::ROLE_MEMBER);

        $this->emailInvitation($invitation);

        if ($existingUser) {
            // event(new UserInvitedToTeam($team, $existingUser));
        }

        return $invitation;
    }

    /**
     * E-mail the given invitation instance.
     *
     * @param  \Laravel\Spark\Invitation  $invitation
     * @return void
     */
    protected function emailInvitation($invitation)
    {
        Mail::send($this->view($invitation), compact('invitation'), function ($m) use ($invitation) {
            $m->to($invitation->email)->subject(__('New Invitation!'));
        });
    }

    /**
     * @param Team $team
     * @param string $email
     * @param $existingUser
     * @param string $role
     * @return mixed
     * @throws \Exception
     */
    protected function createInvitation($team, $email, $existingUser, $role)
    {
        return $team->invitations()->create([
            'id' => Uuid::uuid4(),
            'user_id' => $existingUser ? $existingUser->id : null,
            'role' => $role,
            'email' => $email,
            'token' => Str::random(40),
        ]);
    }

    /**
     * Get the proper e-mail view for the given invitation.
     *
     * @param Invitation $invitation
     * @return string
     */
    protected function view(Invitation $invitation)
    {
        return $invitation->user_id
            ? 'teams.emails.invitation-to-existing-user'
            : 'teams.emails.invitation-to-new-user';
    }
}
