<?php namespace Cargie\Booking\Models;

use Model;
use October\Rain\Database\Traits\Validation;

/**
 * Valuation Model
 */
class Valuation extends Model
{
    use Validation;
    /**
     * @var string The database table used by the model.
     */
    public $table = 'cargie_booking_valuations';
    public $rules = [
        'days' => 'required|min:1',
        'rate' => 'required|numeric|min:0',
        'type' => 'required|in:FO,TO,FR',
    ];
    public $timestamps = false;
    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];
    protected $casts = [
        'days' => 'array',
    ];
    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Relations
     */
    public $hasOne = [

    ];
    public $hasMany = [];
    public $belongsTo = [
        'season' => [
            Season::class,
            'table' => 'cargoe_booking_season',
        ],
    ];
    public $belongsToMany = [];
    public $morphTo = [
        'valuable' => [],
    ];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    public function getRateTypeOptions()
    {
        if ($this->type == "TO") {
            return [
                'P' => '% for all days',
                'F' => 'for all days',
            ];
        } else {
            return [
                'P' => '% from the cost per day',
                'F' => 'per day',
            ];
        }
    }

    public function getRateAttribute($value)
    {
        if ($this->rate_type == "P") {
            return (double) $value;
        }
        return $value;
    }

    public function getRateTypeListAttribute()
    {
        if ($this->type == "TO") {
            if ($this->rate_type == "F") {
                return $this->rate . " % for all days";
            }
            return $this->rate . " for all days";
        } else {
            if ($this->rate_type == "F") {
                return $this->rate . " per day";
            }
            return $this->rate . " % from the cost per day";
        }
    }

    public function filterFields($field, $context = null)
    {
        if ($this->type == "FR") {
            $field->days->type = "partial";
        } else {
            $field->days->type = "number";
        }
    }
}
