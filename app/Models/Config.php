<?php

namespace App\Models;

class Config extends BaseModel
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
     * ConfigType relationship
     *
     * @param null
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(ConfigType::class, 'type_id');
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
