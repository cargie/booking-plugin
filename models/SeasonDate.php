<?php namespace Cargie\Booking\Models;

use Model;
use October\Rain\Database\Traits\Validation;

/**
 * SeasonDates Model
 */
class SeasonDate extends Model
{
    use Validation;
    /**
     * @var string The database table used by the model.
     */
    public $table = 'cargie_booking_season_dates';

    public $rules = [
        'date' => 'required',
    ];
    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'date', 'season_id',
    ];

    public $timestamps = false;
    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [
        'season' => [
            Season::class,
            'table' => 'cargie_booking_seasons',
        ],
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
