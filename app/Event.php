<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Event extends Model
{
    protected $connection = 'archive';
    protected $collection = 'events';
    protected $dates = ['created', 'received'];

    /**
     * Get type name
     *
     * @return string
     */
    public function getTypeNameAttribute()
    {
        if ($this->type == 1) {
            return "SECURITY";
        }
        return "UNKNOWN";
    }

    /**
     * Get level name
     *
     * @return string
     */
    public function getLevelNameAttribute()
    {
        switch($this->level) {
            case 0: return "INFO";
            case 1: return "LOW";
            case 2: return "MEDIUM";
            case 3: return "HIGH";
            case 4: return "CRITICAL";
        }
        return "UNKNOWN";
    }
}