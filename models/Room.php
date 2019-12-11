<?php namespace Cargie\Booking\Models;

use Model;
use Cargie\Booking\Models\RoomType;
use October\Rain\Database\Traits\Sortable;
use \October\Rain\Database\Traits\Validation;

/**
 * Room Model
 */
class Room extends Model
{
    use Validation, Sortable;
    /**
     * @var string The database table used by the model.
     */
    public $table = 'cargie_booking_rooms';

    // const PARENT_ID = 'room_type_id';

    const SORT_ORDER = 'priority';

    public $rules = [
        'name' => 'required|min:3',
        'slug' => 'required|min:3|unique:cargie_booking_rooms,slug',
        'rate' => 'required|numeric|min:0',
        'capacity' => 'required|numeric|min:1',
        // 'priority' => 'required|numeric',
        // 'room_type' => 'required|exists:cargie_booking_room_types,id',
    ];

    public $customMessages = [
    ];
    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name', 'slug', 'description',
        'rate', 'capacity', 'is_enable',
        'cost',
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [
        // 'valuations' => [
        //     Valuation::class,
        //     'table' => 'cargie_booking_valuations',
        // ],
        'bookings' => [
            BookingRoom::class,
        ]
    ];
    public $hasManyThrough = [
        'booked_dates' => [
            BookingDate::class,
            'through' => BookingRoom::class,
        ]  
    ];
    public $belongsTo = [
        'room_type' => [
            'Cargie\Booking\Models\RoomType',
        ],
        'cost' => [
            'Cargie\Booking\Models\VisitorCost',
            'key' => 'visitor_cost_id',
        ],
    ];
    public $belongsToMany = [
        'rates' => [
            Season::class,
            'table' => 'cargie_booking_room_rates',
            'pivot' => ['rate', 'type'],
            'pivotModel' => RoomRatePivot::class,
        ],
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [
        'valuations' => [
            Valuation::class,
            'table' => 'cargie_booking_valuations',
            'name' => 'valuable',
        ],
    ];
    public $morphToMany = [
        // 'rates' => [
        //     Rate::class,
        //     'table' => 'cargie_booking_ratables',
        //     'name' => 'ratable',
        //     // 'pivot' => ['rate', 'type'],
        //     // 'pivotModel' => RoomRatePivot::class,
        // ],
    ];
    public $attachOne = [
        'featured_image' => \System\Models\File::class,
    ];
    public $attachMany = [
        'images' => \System\Models\File::class,
    ];

    public function filterFields($fields, $context = null)
    {
        if ($context == "create") {
            if ($this->room_type) {
                $parent = $this->room_type;
                $fields->capacity->value = $parent->capacity;
                $fields->rate->value = $parent->rate;
            }
        }
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_enable', 1);
    }

    public function scopeEnabled($query)
    {
        return $query->where('is_enable', 1);
    }

    public function getDropDownOptions($field_name = null, $key_value = null)
    {
        if ($field_name == "room_type") {
            $rooms = RoomType::active()->get();
            if ($rooms) {
                return array_merge(
                    ['0' => '-- parent --'],
                    $rooms->lists('name', 'id')
                );
            } else {
                return ['' => '-- parent --'];
            }
        }
    }
}
