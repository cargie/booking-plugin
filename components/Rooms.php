<?php

namespace Cargie\Booking\Components;

use Cms\Classes\ComponentBase;
use Cargie\Booking\Models\RoomType;

class Rooms extends ComponentBase
{
    public $guest;

    public $check_in;

    public $check_out;

    public $rooms;
    
    public $searching = false;

    public function componentDetails()
    {
        return [
            'name' => 'Rooms lists',
            'description' => 'List all rooms with filter'
        ];
    }

    public function defineProperties()
    {
        return [
            'guest' => [
                'title' => 'Number of guest',
                'type' => 'string',
                'min' => 0,
            ],
            'check_in' => [
                'title' => 'Check In',
                'type' => 'string',
                'default' => '',
            ],
            'check_out' => [
                'title' => 'Check Out',
                'type' => 'string',
                'default' => '',
            ],
            'roomPage' => [
                'title' => 'Room Page',
                'type' => 'string',
                'default' => '/rooms'
            ]
        ];
    }

    public function onRender()
    {

    }

    protected function listRoomTypes() {

        $query = RoomType::available()->active();

        if ($this->property('check_in') && $this->property('check_out')) {
            $query = $query->whereHas('rooms', function ($q) {
                        $q->where('is_enable', 1)
                            ->whereDoesntHave('booked_dates', function ($q) {
                                $q->whereBetween('date', [
                                    $this->property('check_in'),
                                    $this->property('check_out')
                                ]);
                            });
                    })->with([
                        'rooms' => function ($q) {
                            $q->whereDoesntHave('booked_dates', function ($q) {
                                $q->whereBetween('date', [
                                    $this->property('check_in'),
                                    $this->property('check_out')
                                ]);
                            });
                        }
                    ]);
        }

        if ($this->property('guest')) {
            $query = $query->where('capacity', '>=', $this->property('guest'));
        }
        $this->searching = $this->page['searching'] = false;
        if ($this->property('guest') && $this->property('check_in') && $this->property('check_out')) {
            $this->searching = $this->page['searching'] = true;
        }

        $this->rooms = $this->page['rooms'] = $query->get()
                            ->transform(function ($room) {
                                $lroom = $room;

                                $lroom['url'] = url($this->property('roomPage') . $room->slug . '?') . http_build_query([
                                    'check_in' => $this->property('check_in'),
                                    'check_out' => $this->property('check_out'),
                                    'guest' => $this->property('guest'),
                                ]);

                                return $lroom;
                            });
    }
}