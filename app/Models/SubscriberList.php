<?php namespace App\Models;

use App\Traits\Uuid;

class SubscriberList extends BaseModel
{
    use Uuid;

    protected $fillable = [
        'name',
    ];

    public function subscribers()
    {
        return $this->hasMany(Subscriber::class);
    }

    public function active_subscribers()
    {
        return $this->hasMany(Subscriber::class)
            ->whereNull('unsubscribed_at');
    }

    public function getSubscribersCountAttribute()
    {
        if ( ! array_key_exists('SubscriberCount', $this->relations))
        {
            $this->load('subscriberCount');
        }

        $related = $this->getRelation('subscriberCount')->first();

        return ($related) ? $related->aggregate : 0;
    }

    public function subscriberCount()
    {
        return $this->hasMany(Subscriber::class)
            ->selectRaw('count(subscribers.id) as aggregate')
            ->groupBy('subscribers.id');
    }
}
