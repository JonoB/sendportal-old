<?php

namespace App\Models;

class Config extends BaseModel
{

    /**
     * @var array
     */
    protected $fillable = [
        'type_id',
        'settings',
    ];

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