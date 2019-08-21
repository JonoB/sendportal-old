<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The guarded attributes on the model.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Returns expires date
     *
     * @return mixed
     */
    public function getExpiresAtAttribute()
    {
        return $this->created_at->addWeek();
    }

    /**
     * Get the team that owns the invitation.
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Determine if the invitation is expired.
     *
     * @return bool
     */
    public function isExpired()
    {
        return Carbon::now()->subWeek()->gte($this->created_at);
    }

}
