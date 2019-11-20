<?php namespace Cargie\Booking\Models;

use Model;
use Illuminate\Support\Facades\Log;
use October\Rain\Database\Traits\Validation;

/**
 * VisitorCost Model
 */
class VisitorCost extends Model
{
    use Validation;
    /**
     * @var string The database table used by the model.
     */
    public $table = 'cargie_booking_visitor_costs';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];
    protected $jsonable = [
        'children_rates',
        'adult_rates',
    ];
    protected $casts = [
    ];
    public $rules = [
        'name' => 'required|min:3',
        'slug' => 'required|min:3|unique:cargie_booking_visitor_costs,slug',
        'adult_rates' => 'required',
    ];
    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [
        'rooms' => [
            'Cargie\Booking\Models\Room',
        ],
    ];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    public function getRateByVisitors($adult, $children, $room_rate)
    {
        $total = 0;
        if ($adult) {
            $rate = collect($this->adult_rates);
            $cost = $rate->sortByDesc('adult_number');
            while ($adult >= 0) {
                $adult_rate = $cost->where('adult_number', $adult)->first();
                if ($adult_rate) {
                    if ($adult_rate['type'] == 'percentage') {
                        $total += ($room_rate * ($adult_rate['adult_cost'] / 100));
                    } else {
                        $total += $adult_rate['adult_cost'];
                    }
                }
                $adult -= 1;
            }
        }
        if ($children) {
            $rate = collect($this->children_rates);
            $cost = $rate->sortByDesc('children_number');
            while ($children >= 1) {
                $children_rate = $cost->where('children_number', $children)->first();
                if ($children_rate['type'] == 'percentage') {
                    $total += ($room_rate * ($children_rate['children_cost'] / 100));
                } else {
                    $total += $children_rate['children_cost'];
                }
                $children -= 1;
            }
        }
        return $total;
    }
}
