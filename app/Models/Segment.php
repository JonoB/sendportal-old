<?php namespace App\Models;

class Segment extends BaseModel
{
    protected $fillable = [
        'name',
    ];

    protected $withCount = [
        'subscribers'
    ];

    /**
     * Subscribers of this Segment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function subscribers()
    {
        return $this->belongsToMany(Subscriber::class)->withTimestamps();
    }
}
