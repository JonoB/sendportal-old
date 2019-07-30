<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends BaseModel
{
    protected $guarded = [];

    public $dates = [
        'created_at',
        'updated_at',
        'sent_at',
        'delivered_at',
        'opened_at',
        'clicked_at',
    ];

    // we can't use boolean fields on this model because
    // we have multiple points to update from the controller
    protected $booleanFields = [];

    /**
     * @return BelongsTo
     */
    public function subscriber(): BelongsTo
    {
        return $this->belongsTo(Subscriber::class);
    }
}
