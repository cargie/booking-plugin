<?php namespace Cargie\Booking\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Room Types Back-end Controller
 */
class RoomTypes extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.RelationController',
        'Backend.Behaviors.ReorderController',
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $relationConfig = 'config_relation.yaml';
    public $reorderConfig = 'config_reorder.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Cargie.Booking', 'booking', 'roomtypes');
    }
}
