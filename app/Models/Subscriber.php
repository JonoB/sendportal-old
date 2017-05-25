<?php namespace App\Models;

use App\Traits\Uuid;

class Subscriber extends BaseModel
{
    use Uuid;

    protected $fillable = [
        'email',
        'first_name',
        'last_name',
        'unsubscribed_at',
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
