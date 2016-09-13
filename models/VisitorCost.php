<?php namespace Cargie\Booking\Models;

use Model;
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
            $cost = $rate->sortByDesc('adult_number')->lists('adult_number', 'adult_cost');
            while ($adult >= 0) {
                $adult_rate = $cost->search($adult);
                if ($adult_rate) {
                    $total += ($room_rate * ($adult_rate / 100));
                    break;
                } else {
                    $adult -= 1;
                }
            }
        }
        if ($children) {
            $rate = collect($this->children_rates);
            $cost = $rate->sortByDesc('children_number')->lists('children_number', 'children_cost');
            while ($children >= 1) {
                $children_rate = $cost->search($children);
                if ($children_rate) {
                    $total += ($room_rate * ($children_rate / 100)) - $room_rate;
                    break;
                }
                $children -= 1;
            }
        }
        return $total;
    }
}
