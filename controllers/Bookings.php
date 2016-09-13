<?php namespace Cargie\Booking\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Carbon\Carbon;

/**
 * Bookings Back-end Controller
 */
class Bookings extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.RelationController',
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $relationConfig = 'config_relation.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Cargie.Booking', 'booking', 'bookings');
    }

    public function formAfterSave($model)
    {
        $model->dates()->delete();
        $from = Carbon::parse(post('Booking.from'));
        $to = Carbon::parse(post('Booking.to'));
        while ($from->lte($to)) {
            $model->dates()->create([
                'date' => $from->format('Y-m-d'),
            ]);
            $from->addDay();
        }
    }

    public function formAfterDelete($model)
    {
        $model->dates()->delete();
    }
}
