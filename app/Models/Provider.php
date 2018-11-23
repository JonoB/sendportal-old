<?php

namespace App\Models;

class Provider extends BaseModel
{

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'type_id',
        'settings',
    ];

    /**
     * ProviderType relationship
     *
     * @param null
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(ProviderType::class, 'type_id');
    }

    /**
     * Campaigns relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function campaigns()
    {
        return $this->hasMany(Campaign::class, 'provider_id');
    }

    /**
     * @param array $data
     */
    public function setSettingsAttribute(array $data)
    {
        $this->attributes['settings'] = encrypt(json_encode($data));
    }

    /**
     * @param $value
     * @return string
     */
    public function getSettingsAttribute($value)
    {
        return json_decode(decrypt($value), true);
    }
}
