<?php namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subscriber extends BaseModel
{
    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model)
        {
            $model->hash = \Ramsey\Uuid\Uuid::uuid4()->toString();
        });
    }

    /**
     * Segments this subscriber is assigned to
     *
     * @return BelongsToMany
     */
    public function segments()
    {
        return $this->belongsToMany(Segment::class)->withTimestamps();
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
