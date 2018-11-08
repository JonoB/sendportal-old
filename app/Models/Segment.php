<?php namespace App\Models;

class Segment extends BaseModel
{
    protected $fillable = [
        'name',
    ];

    protected $withCount = [
        'subscribers', 'active_subscribers'
    ];

    /**
     * Subscribers of this Segment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function subscribers()
    {
        return $this->belongsToMany(Subscriber::class)
            ->withTimestamps()
            ->withPivot('unsubscribed_at');
    }

    /**
     * Active Subscribers of this Segment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function active_subscribers()
    {
        return $this->subscribers()
            ->whereNull('unsubscribed_at');
    }
}
