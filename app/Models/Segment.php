<?php namespace App\Models;

class Segment extends BaseModel
{
    protected $fillable = [
        'name',
    ];

    public function subscribers()
    {
        return $this->belongsToMany(Subscriber::class)
            ->withTimestamps();
    }

    public function active_subscribers()
    {
        return $this->subscribers()
            ->whereNull('unsubscribed_at');
    }

    public function getSubscribersCountAttribute()
    {
        if ( ! array_key_exists('subscribers', $this->relations))
        {
            $this->load('subscribers');
        }

        $related = $this->getRelation('subscriberCount')->first();

        return ($related) ? $related->aggregate : 0;
    }

    public function subscriberCount()
    {
        return $this->belongsToMany(Subscriber::class)
            ->selectRaw('count(subscribers.id) as aggregate')
            ->groupBy('subscribers.id');
    }
}
