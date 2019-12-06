<?php namespace Cargie\Booking\Models;

use Cargie\Booking\Models\BookingRoom;
use Illuminate\Support\Carbon;
use Model;

/**
 * Model
 */
class BookingRoom extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    // use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at', 'start_at', 'end_at'];

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'cargie_booking_booking_rooms';

    /**
     * @var array Validation rules
     */
    public $rules = [
        //
    ];

    public $belongsTo = [
        'room' => [
            Room::class,
            'table' => 'cargie_booking_rooms',
        ],
        'booking' => [
            Booking::class,
            'table' => 'cargie_booking_bookings',
        ],
    ];

    public $hasMany = [
        'dates' => [
            BookingDate::class,
            'table' => 'cargie_booking_booking_dates'
        ]
    ];

    public $morphOne = [
        'invoice_item' => [
            'Responsiv\Pay\Models\InvoiceItem',
            'name' => 'related',
            'delete' => true,
        ]
    ];

    public function getRoomOptions()
    {
        return  [];
    }

    public function filterFields($fields, $context = null)
    {
        
        if($context == "create") {
            if ($this->start_at) {
                $fields->end_at->value = '';
            }
            if ($this->end_at) {
                $rooms = Room::available()->orderBy('room_type_id')->orderBy('priority', 'desc')->get();
                $booked_rooms = BookingDate::whereBetween('date',
                    [Carbon::parse($this->start_at)->format('Y-m-d'), Carbon::parse($this->end_at)->format('Y-m-d')]
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
                    $from = Carbon::parse($this->start_at);
                    $to = Carbon::parse($this->end_at);
                    $visitor_days = $from->diffInDays($to);
                    $visitor_rate = $selected_room->cost->getRateByVisitors($this->adult, $this->children, $selected_room->rate);
                    $fields->rate->value = $visitor_rate * $visitor_days;
                } else {

                }
            }

        } elseif ($context == "update") {
            $booking = BookingRoom::find($this->id);
            if ($this->start_at) {
                // $fields->end_at->value = '';
            }
            if ($this->end_at) {
                if ($booking->dates()->get()->last()->date->format("y-m-d") != Carbon::parse($this->end_at)->format("y-m-d")) {
                    $fields->room_id->value = -1;
                    $fields->adult->value = -1;
                    $fields->children->value = -1;
                    // $fields->rate->value = "";
                }
                $rooms = Room::available()->orderBy('priority', 'desc')->get();
                $booked_rooms = BookingDate::whereBetween('date',
                    [Carbon::parse($this->start_at)->format('Y-m-d'), Carbon::parse($this->end_at)->format('Y-m-d')]
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
                // if (is_numeric($this->children)) {
                //     $from = Carbon::parse($this->start_at);
                //     $to = Carbon::parse($this->end_at);
                //     $visitor_days = $from->diffInDays($to) + 1;
                //     $visitor_rate = $selected_room->cost->getRateByVisitors($this->adult, $this->children, $selected_room->rate);
                //     $fields->rate->value = $visitor_rate * $visitor_days;
                // } else {

                // }
            }
        }
    }

    public function afterCreate()
    {
        $start = $this->start_at;
        $end = $this->end_at;
        while ($start->lte($this->end_at)) {
            $this->dates()->create([
                'date' => $start->format('Y-m-d'),
            ]);
            $start->addDay();
        }

        $this->createInvoiceItem();
    }

    protected function createInvoiceItem()
    {
        $booking = $this->booking;

        if (!$this->invoice_item) {

            if ($booking->invoice) {
                $item = $booking->invoice->items()->create([
                    'description' => $this->room->name,
                    'quantity' => 1,
                    'price' => $this->getCalculatedRate(),
                ]);
                $item->related = $this;
                $item->save();

                $booking->invoice->save();
            }
        } else {
            if ($booking->invoice) {
                $item = $this->invoice_item->fill([
                    'description' => $this->room->name,
                    'quantity' => 1,
                    'price' => $this->getCalculatedRate(),
                ]);
                $item->related = $this;
                $item->save();

                $booking->invoice->save();
            }
        }
    }

    public function afterUpdate()
    {
        if (($this->start_at != $this->original['start_at']) || ($this->end_at != $this->original['end_at'])) {
            $this->dates()->delete();
            $start = $this->start_at;
            $end = $this->end_at;
            while ($start->lte($this->end_at)) {
                $this->dates()->create([
                    'date' => $start->format('Y-m-d'),
                ]);
                $start->addDay();
            }
        }
        $this->createInvoiceItem();
    }

    public function afterDelete()
    {
        if ($this->invoice_item) {
            $invoice_item = $this->invoice_item;
            $this->invoice_item()->delete();
            $invoice_item->invoice->save();
        }
    }

    public function getCalculatedRate()
    {
        $visitor_rate = $this->room->cost->getRateByVisitors($this->adult, $this->children, $this->room->rate);
        $visitor_days = $this->start_at->diffInDays($this->end_at) + 1;
        return $visitor_rate * $visitor_days;
    }
}
