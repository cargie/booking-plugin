<?php namespace Cargie\Booking\Models;

use Model;
use October\Rain\Database\Traits\Validation;

/**
 * Season Model
 */
class Season extends Model
{
    use Validation;
    /**
     * @var string The database table used by the model.
     */
    public $table = 'cargie_booking_seasons';

    public $rules = [
        'name' => 'required|min:3',
        'slug' => 'required|min:3|unique:cargie_booking_seasons,slug',
        'description' => 'required',
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
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [
        'dates' => [
            SeasonDate::class,
            'table' => 'cargie_booking_season_dates',
        ],
        'valuations' => [
            Room::class,
            'table' => 'cargie_booking_valuations',
        ],
        'rates' => [
            Room::class,
            'table' => 'cargie_booking_rates',
            'pivot' => ['rate', 'type'],
            'timestamps' => true,
        ],
    ];
    public $belongsTo = [];
    public $belongsToMany = [
        // 'rates' => [
        //     Room::class,
        //     'table' => 'cargie_booking_rates',
        //     'pivot' => ['enabled', 'rate', 'type'],
        //     'timestamps' => true,
        // ],
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
