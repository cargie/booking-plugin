<?php namespace Cargie\Booking;

use Backend\Facades\Backend;
use Illuminate\Support\Carbon;
use System\Classes\PluginBase;
use Illuminate\Support\Facades\Event;

/**
 * Booking Plugin Information File
 */
class Plugin extends PluginBase
{

    public $require = [
        'Rainlab.User', 'Responsiv.Pay'
    ];
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name' => 'Booking',
            'description' => 'Booking Solution for October CMS',
            'author' => 'Cargie',
            'icon' => 'icon-book',
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
        Event::listen('backend.menu.extendItems', function ($manager) {
            // $manager->removeMainMenuItem('Rainlab.User', 'user');
            // $manager->addMainMenuItems('Rainlab.User', [
            //     'user' => [
            //         'label' => 'Customers',
            //         'icon' => 'icon-user',
            //         'url' => Backend::url('rainlab/user/users'),
            //         'order' => 301,
            //     ],
            // ]);
        });
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return [
            'Cargie\Booking\Components\Rooms' => 'rooms',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'cargie.booking.some_permission' => [
                'tab' => 'Booking',
                'label' => 'Some permission',
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        // return [];

        return [
            'booking' => [
                'label' => 'Booking',
                'url' => Backend::url('cargie/booking/bookings'),
                'icon' => 'icon-book',
                'permissions' => ['cargie.booking.*'],
                'order' => 300,
                'sideMenu' => [
                    'bookings' => [
                        'label' => 'Bookings',
                        'url' => Backend::url('cargie/booking/bookings'),
                        'icon' => 'icon-book',
                    ],
                    'calendar' => [
                        'label' => 'Calendar',
                        'url' => Backend::url('cargie/booking/calendar'),
                        'icon' => 'icon-calendar',
                    ],
                    'rooms' => [
                        'label' => 'Rooms',
                        'url' => Backend::url('cargie/booking/rooms'),
                        'icon' => 'icon-building-o',
                    ],
                    'costs' => [
                        'label' => 'Adv Costs',
                        'url' => Backend::url('cargie/booking/costs'),
                        'icon' => 'icon-money',
                    ],
                    'seasons' => [
                        'label' => 'Season Filters',
                        'url' => Backend::url('cargie/booking/seasons'),
                        'icon' => 'icon-leaf',
                    ],
                ],
            ],
        ];
    }

    public function registerFormWidgets()
    {
        return [
            'Cargie\Booking\FormWidgets\DatesPicker' => [
                'label' => 'Date Picker',
                'code' => 'datespicker',
            ],
        ];
    }

    public function registerListColumnTypes()
    {
        return [
            // A local method, i.e $this->evalUppercaseListColumn()
            'uppercase' => [$this, 'evalUppercaseListColumn'],

            // Using an inline closure
            'loveit' => function ($value) {
                return 'I love ' . $value;
            },
            'ucfirst' => function ($value) {
                return ucfirst($value);
            }
        ];
    }

    public function evalUppercaseListColumn($value, $column, $record)
    {
        return strtoupper($value);
    }

    public function registerMarkupTags()
    {
        return [

            'functions' => [
                'parse' => function ($value) {
                    return Carbon::parse($value);
                }
            ]
        ];
    }
}
