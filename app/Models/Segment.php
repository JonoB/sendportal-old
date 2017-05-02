<?php

namespace App\Models;

class Segment extends BaseModel
{
    protected $fillable = [
        'name',
    ];

    public function getContactsCountAttribute()
    {
        if ( ! array_key_exists('contactsCount', $this->relations))
        {
            $this->load('contactsCount');
        }

        $related = $this->getRelation('contactsCount')->first();

        return ($related) ? $related->aggregate : 0;
    }

    public function contacts()
    {
        return $this->belongsToMany(Contact::class);
    }

    public function contactsCount()
    {
        return $this->belongsToMany(Contact::class)->selectRaw('count(contacts.id) as aggregate')->groupBy('pivot_segment_id');
    }
}
