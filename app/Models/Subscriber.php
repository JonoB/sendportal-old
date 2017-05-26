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
    ];

    public function subscriberList()
    {
        return $this->belongsTo(SubscriberList::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
