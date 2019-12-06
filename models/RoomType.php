<?php namespace Cargie\Booking\Models;

use Model;
use October\Rain\Database\Traits\Sortable;
use October\Rain\Database\Traits\Validation;

/**
 * RoomType Model
 */
class RoomType extends Model
{
    use Validation, Sortable;
    /**
     * @var string The database table used by the model.
     */
    public $table = 'cargie_booking_room_types';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];
    public $rules = [
        'name' => 'required|min:3',
        'slug' => 'required|min:3|unique:cargie_booking_rooms,slug',
        'rate' => 'required|numeric|min:0',
        'capacity' => 'required|numeric|min:1',
        'description' => 'required',
        'featured_image' => 'required',
        'images' => 'required|min:2',
        'cost' => 'required',
    ];

    protected $casts = [
        'meta' => 'array'
    ];
    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name', 'slug', 'description',
        'rate', 'capacity', 'is_enable',
    ];
    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [
        'rooms' => [
            Room::class,
            'table' => 'cargie_booking_rooms',
            'order' => 'priority',
        ],
    ];
    public $belongsTo = [
        'cost' => [
            'Cargie\Booking\Models\VisitorCost',
            'key' => 'visitor_cost_id',
        ],
    ];
    public $belongsToMany = [
        'rates' => [
            Season::class,
            'table' => 'cargie_booking_room_type_rates',
            'pivot' => ['rate', 'type'],
            'pivotModel' => RoomTypeRatePivot::class,
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
    public $attachOne = [
        'featured_image' => \System\Models\File::class,
    ];
    public $attachMany = [
        'images' => \System\Models\File::class,
    ];

    public function scopeActive($query)
    {
        return $query->where('is_enable', 1);
    }
}
