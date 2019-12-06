<?php namespace Cargie\Booking\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use October\Rain\Database\Pivot;

class BookingRoomPivot extends Pivot
{
    use SoftDeletes;
}
