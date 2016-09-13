<?php namespace Cargie\Booking\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Cargie\Booking\Models\Season;

/**
 * Seasons Back-end Controller
 */
class Seasons extends Controller
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

        BackendMenu::setContext('Cargie.Booking', 'booking', 'seasons');
    }

    public function formAfterSave($model)
    {
        $model->dates()->delete();
        // collect(post('Season.dates'))->each(function ($item, $key) use ($model) {
        //     $model->dates()->create(['date' => $item]);
        // });
        foreach (post('Season.dates') as $date) {
            $model->dates()->create(['date' => $date]);
        }
    }

    public function formAfterDelete($model)
    {
        $model->dates()->delete();
    }
}
