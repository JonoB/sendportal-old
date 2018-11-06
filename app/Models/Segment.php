<?php namespace App\Models;

use App\Traits\Uuid;

class Segment extends BaseModel
{
    use Uuid;

    protected $fillable = [
        'name',
    ];

    public function subscribers()
    {
        return $this->belongsToMany(Subscriber::class)
            ->withTimestamps()
            ->withPivot('unsubscribed_at');
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
