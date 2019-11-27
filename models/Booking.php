<?php namespace Cargie\Booking\Models;

use Carbon\Carbon;
use Cargie\Booking\Models\BookingDate;
use Cargie\Booking\Models\Room;
use Model;
use October\Rain\Database\Traits\Purgeable;
use October\Rain\Database\Traits\Validation;

/**
 * Booking Model
 */
class Booking extends Model
{
    use Purgeable, Validation;
    /**
     * @var string The database table used by the model.
     */
    public $table = 'cargie_booking_bookings';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];
    protected $purgeable = ['from', 'to'];
    protected $rules = [
        'room_id' => 'required|exists:cargie_booking_rooms,id',
        'adult' => 'numeric|min:0',
        'children' => 'numeric|min:0|required',
        'rate' => 'required|numeric|min:0',
        // 'customer' => 'required',
    ];
    public $attributes = [
        'status' => 'approved',
        'source' => 'website',
    ];
    public $customMessages = [
        'children.numeric' => ':attribute, Please select a number',
    ];
    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [
        'dates' => [
            'Cargie\Booking\Models\BookingDate',
            'orderBy' => 'date',
        ],
    ];
    public $belongsTo = [
        'room' => [
            Room::class,
            'table' => 'cargie_booking_rooms',
        ],
        'customer' => [
            'Rainlab\User\Models\User',
            'key' => 'user_id',
        ],
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [
        'invoice' => [
            'Cargie\Booking\Models\Invoice', 'name' => 'related'
        ]
    ];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    public function filterFields($fields, $context = null)
    {
        if ($context == "create") {
            if ($this->from) {
                $fields->to->value = '';
            }
            if ($this->to) {
                $rooms = Room::available()->orderBy('room_type_id')->orderBy('priority', 'desc')->get();
                $booked_rooms = BookingDate::whereBetween('date',
                    [Carbon::parse($this->from)->format('Y-m-d'), Carbon::parse($this->to)->format('Y-m-d')]
                )->whereHas('booking', function ($query) {
                    $query->where('status', 'approved');
                })->get()->transform(function ($item, $key) {
                    return $item->booking->room;
                })->unique();
                $fields->room_id->value = -1;
                $available_rooms = $rooms->diff($booked_rooms)->lists('name', 'id');
                if ($available_rooms) {
                    $fields->room_id->options = [0 => '-- select room --'] + $available_rooms;
                } else {
                    $fields->room_id->options = [
                        null => 'No rooms available',
                    ];
                }
            } else {
                $fields->room_id->emptyOption = '-- select room --';
            }
            if ($this->room_id) {
                $fields->adult->value = -1;
                $selected_room = $this->room->find($this->room_id);
                $capacity = $selected_room->capacity;
                $range = range(1, $capacity);
                $adult_merge = array_merge(['null' => '-- select number --'], $range);
                $fields->adult->options = array_combine($adult_merge, $adult_merge);
                $children_range = range(0, $capacity -= (int) $this->adult);
                $merge = array_merge(['null' => '-- select number --'], $children_range);
                $fields->children->value = -1;
                $fields->children->options = array_combine($merge, $merge);
                if (is_numeric($this->children)) {
                    $from = Carbon::parse($this->from);
                    $to = Carbon::parse($this->to);
                    $visitor_days = $from->diffInDays($to);
                    $visitor_rate = $selected_room->cost->getRateByVisitors($this->adult, $this->children, $selected_room->rate);
                    $fields->rate->value = $visitor_rate * $visitor_days;
                } else {

                }
            }

        } elseif ($context == "update") {
            $booking = Booking::find($this->id);
            if ($this->from) {
                $fields->to->value = '';
            }
            if ($this->to) {
                if ($booking->dates()->get()->last()->date->format("y-m-d") != Carbon::parse($this->to)->format("y-m-d")) {
                    $fields->room_id->value = -1;
                    $fields->adult->value = -1;
                    $fields->children->value = -1;
                    $fields->rate->value = "";
                }
                $rooms = Room::available()->orderBy('priority', 'desc')->get();
                $booked_rooms = BookingDate::whereBetween('date',
                    [Carbon::parse($this->from)->format('Y-m-d'), Carbon::parse($this->to)->format('Y-m-d')]
                )->get()->filter(function ($item) {
                    return $item->reservation;
                })->transform(function ($item, $key) {
                    return $item->reservation->room;
                })->unique();
                $available_rooms = $rooms->diff($booked_rooms)->lists('name', 'id');
                if ($available_rooms) {
                    $fields->room_id->options = [0 => '-- select room --'] + $available_rooms;
                } else {
                    $fields->room_id->options = [
                        null => 'No rooms available',
                    ];
                }
            } else {
                $fields->room_id->emptyOption = '-- select room --';
            }
            if (is_numeric($this->room_id) && $this->room_id != 0) {
                $selected_room = $this->room->find($this->room_id);
                $capacity = $selected_room->capacity;
                $range = range(1, $capacity);
                $adult_merge = array_merge(['null' => '-- select number --'], $range);
                $children_range = range(0, $capacity -= $this->adult);
                $fields->adult->options = array_combine($adult_merge, $adult_merge);
                $merge = array_merge(['null' => '-- select number --'], $children_range);
                $fields->children->options = array_combine($merge, $merge);
                if (is_numeric($this->children)) {
                    $from = Carbon::parse($this->from);
                    $to = Carbon::parse($this->to);
                    $visitor_days = $from->diffInDays($to) + 1;
                    $visitor_rate = $selected_room->cost->getRateByVisitors($this->adult, $this->children, $selected_room->rate);
                    $fields->rate->value = $visitor_rate * $visitor_days;
                } else {

                }
            }
        }
    }

    public function getFromAttribute($value)
    {
        if ($this->exists) {
            $date = $this->dates()->first()->date;
            if ($date->format("m-d-Y") == Carbon::parse($value)) {
                return $date;
            } else {
                return $value ?? $date;
            }
        }
        return $value;
    }

    public function getToAttribute($value)
    {
        if ($this->exists) {
            $date = $this->dates()->get()->last()->date;
            if ($date->format("m-d-Y") == Carbon::parse($value)) {
                return $date;
            } else {
                return $value ?? $date;
            }
        }
        return $value;
    }

    public function getBookingDatesAttribute()
    {
        $from = $this->dates()->first()->date;
        $last = $this->dates()->get()->last()->date;
        return $from->format("M d, Y") . ' - ' . $last->format("M d, Y");
    }
}
