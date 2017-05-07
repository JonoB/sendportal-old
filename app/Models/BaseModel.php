<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    /**
     * We use the UUID trait in most models by default, so
     * incrementing is set to false by default
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Store which fields are boolean in the model
     *
     * Note that any boolean fields not in the fillable
     * array will not be automatically set in the repo
     *
     * @var array
     */
    protected $booleanFields = [];

    /**
     * Return all boolean fields for the model
     *
     * @return array
     */
    public function getBooleanFields()
    {
        return $this->booleanFields;
    }
}