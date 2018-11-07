<?php namespace App\Models;

use App\Traits\Uuid;

class Segment extends BaseModel
{
    use Uuid;

    protected $fillable = [
        'name',
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

    /**
     * Subscribers count
     *
     * @return Object
     */
    public function subscribersCount()
    {
        return $this->belongsToMany(Subscriber::class)
            ->selectRaw('count(subscribers.id) as aggregate')
            ->groupBy('subscribers.id');
    }

    /**
     * Subscribers count attribute
     *
     * @return int
     */
    public function getSubscribersCountAttribute()
    {
        $related = $this->getRelation('subscribersCount')->first();

        return optional($related)->aggregate ?? 0;
    }

    /**
     * Active subscribers count
     *
     * @return Object
     */
    public function activeSubscribersCount()
    {
        return $this->belongsToMany(Subscriber::class)
            ->selectRaw('count(subscribers.id) as aggregate')
            ->whereNull('unsubscribed_at')
            ->groupBy('subscribers.id');
    }

    /**
     * Active subscribers count attribute
     *
     * @return integer
     */
    public function getActiveSubscribersCountAttribute()
    {
        $related = $this->getRelation('activeSubscribersCount')->first();

        return optional($related)->aggregate ?? 0;
    }
}
