<?php namespace Cargie\Booking\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Rooms Back-end Controller
 */
class Rooms extends Controller
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

        BackendMenu::setContext('Cargie.Booking', 'booking', 'rooms');
    }
}
