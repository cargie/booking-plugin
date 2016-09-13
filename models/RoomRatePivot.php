<?php namespace Cargie\Booking\Models;

use October\Rain\Database\Pivot;

class RoomRatePivot extends Pivot
{
    public function getTypeColumnAttribute()
    {
        if ($this->type == "P") {
            return '% from the cost per day';
        }
        return 'per day';
    }

    public function getRateAttribute($value)
    {
        if ($this->type == "P") {
            return $value + 0;
        }
        return $value;
    }
}
