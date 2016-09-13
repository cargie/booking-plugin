<?php namespace Cargie\Booking\Models;

use Model;

/**
 * ReservationDates Model
 */
class BookingDate extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'cargie_booking_booking_dates';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    public $timestamps = false;
    protected $fillable = ['date'];
    protected $dates = [
        'date',
    ];
    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [
        'booking' => [
            'Cargie\Booking\Models\Booking',
        ],
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

}
