<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends BaseModel
{
    const ROLE_OWNER = 'owner';
    const ROLE_MEMBER = 'member';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'owner_id',
        'trial_ends_at',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'card_brand',
        'card_last_four',
        'card_country',
        'billing_address',
        'billing_address_line_2',
        'billing_city',
        'billing_state',
        'billing_zip',
        'billing_country',
        'extra_billing_information',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'owner_id' => 'int',
        'trial_ends_at' => 'datetime',
    ];

    /**
     * Get the owner's email address.
     *
     * @return string
     */
    public function getEmailAttribute()
    {
        // return $this->owner->email;
    }

    /**
     * Get the owner of the team.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get all of the users that belong to the team.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'team_users')
            ->orderBy('name')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Get all of the team's invitations.
     */
    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    /**
     * The subscriptions that belong to this team.
     *
     * @return HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'team_id')->orderBy('created_at', 'desc');
    }

    /**
     * Determine whether or not the team has a subscription.
     *
     * @return bool
     */
    public function getHasSubscriptionAttribute(): bool
    {
        return (bool)$this->subscriptions->count() > 0;
    }

    /**
     * Determine whether or not the team's trial has expired.
     *
     * @return bool
     */
    public function getTrialHasExpiredAttribute(): bool
    {
        return (bool)$this->trial_ends_at->lte(now());
    }

    /**
     * Determine whether or not the team's trial has expired.
     *
     * @return bool
     */
    public function getHasActiveTrialAttribute(): bool
    {
        $trialEndDate = $this->trial_ends_at;

        return $trialEndDate
            ? (bool)$trialEndDate->gte(now()) && ! $this->has_subscription
            : false;
    }

    /**
     * Determine if we are within 5 days of subscription ending
     *
     * @return bool
     */
    public function getDisplayTrialActivationAttribute(): bool
    {
        if ($this->has_subscription)
        {
            return false;
        }

        return now()->diffInDays($this->trial_ends_at, false) <= 5;
    }

    /**
     * Get the number of days remaining on the team's trial.
     *
     * @return int|null
     */
    public function getTrialDaysRemainingAttribute(): int
    {
        $trialEndDate = $this->trial_ends_at;

        return $trialEndDate
            ? $trialEndDate->diffInDays(now())
            : 0;
    }

    /**
     * Make the team attributes visible for an owner.
     *
     * @return void
     */
    public function shouldHaveOwnerVisibility()
    {
        $this->makeVisible([
            'card_brand',
            'card_last_four',
            'card_country',
            'billing_address',
            'billing_address_line_2',
            'billing_city',
            'billing_state',
            'billing_zip',
            'billing_country',
            'extra_billing_information',
        ]);
    }

    /**
     * Detach all of the users from the team and delete the team.
     *
     * @return void
     */
    public function detachUsersAndDestroy()
    {
        if ($this->subscribed())
        {
            $this->subscription()->cancelNow();
        }

        $this->users()
            ->where('current_team_id', $this->id)
            ->update(['current_team_id' => null]);

        $this->users()->detach();

        $this->delete();
    }
}
