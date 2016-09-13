<?php namespace Cargie\Booking\Models;

use Model;

/**
 * Rate Model
 */
class Rate extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'cargie_booking_rates';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

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
    public $morphTo = [
        // 'rateable' => [],
    ];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
