<?php namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subscriber extends BaseModel
{
    use Uuid;

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * Segments this subscriber is assigned to
     *
     * @return BelongsToMany
     */
    public function segments()
    {
        return $this->belongsToMany(Segment::class)
            ->withTimestamps()
            ->withPivot('unsubscribed_at');
    }

    /**
     * Tags associated with this subscriber
     *
     * @return BelongsToMany
     */
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
