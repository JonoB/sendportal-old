<?php namespace App\Models;

use App\Traits\Uuid;

class Subscriber extends BaseModel
{
    use Uuid;

    protected $fillable = [
        'subscriber_list_id',
        'email',
        'first_name',
        'last_name',
        'unsubscribed_at',
        'meta'
    ];

    public function subscriberList()
    {
        return $this->belongsTo(SubscriberList::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * The concatenated full name of the subscriber
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
