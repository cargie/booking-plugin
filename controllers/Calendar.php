<?php namespace Cargie\Booking\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Backend\Facades\Backend;
use Cargie\Booking\Models\Booking;
use October\Rain\Support\Facades\Flash;

/**
 * Calendar Back-end Controller
 */
class Calendar extends Controller
{
    public $implement = [
        'Backend.Behaviors.ListController'
    ];

    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        $this->addCss('/plugins/cargie/booking/assets/css/fullcalendar.core.css', '4.2.1');
        $this->addCss('/plugins/cargie/booking/assets/css/fullcalendar.daygrid.css', '4.2.1');
        $this->addJs('/plugins/cargie/booking/assets/js/fullcalendar.core.js', '4.2.1');
        $this->addJs('/plugins/cargie/booking/assets/js/fullcalendar.daygrid.js', '4.2.1');

        BackendMenu::setContext('Cargie.Booking', 'booking', 'calendar');
    }

    public function index()
    {
        $this->asExtension('ListController')->index();
        $this->vars['bookings'] = Booking::where('status', '<>', 'declined')->with(['dates', 'room'])->get()->transform(function ($booking) {
            return [
                'id' => $booking->id,
                'title' => $booking->room->name . ' - ' . ucfirst($booking->customer->name) . ' - ' . ucfirst($booking->status),
                'start' => $booking->dates->first()->date->format('Y-m-d'),
                'end' => $booking->dates->last()->date->format('Y-m-d'),
                'color' => $booking->status == 'pending' ? 'gray' : null,
                // 'allDay' => false,
                'data' => $booking->toArray(),
            ];
        });
    }

    public function listExtendRecords($records, $definition)
    {
        return $records;
    }

    public function index_onBookingChangeInfoForm($recordId = null)
    {
        try {
            $booking = Booking::findOrFail(post()['id']);
            $this->vars['id'] = $booking->id;
            $this->vars['url'] = Backend::url(sprintf('cargie/booking/bookings/preview/%s', $booking->id));
            $this->vars['widget'] = $this->makeBookingChangeInfoWidget($booking);
        }
        catch (Exception $ex) {
            $this->handleError($ex);
        }

        return $this->makePartial('change_booking_info_form');
    }

    public function makeBookingChangeInfoWidget($booking)
    {
        $model = $booking;

        $config = $this->makeConfig('~/plugins/cargie/booking/models/booking/update_calendar_change_booking_info_fields.yaml');
        $config->model = $model;

        return $this->makeWidget('Backend\Widgets\Form', $config);
    }

    public function index_onBookingChangeInfo($recordId = null)
    {
        $booking = Booking::findOrFail(post()['id']);

        $widget = $this->makeBookingChangeInfoWidget($booking);

        $data = $widget->getSaveData();

        info($data);
        $booking->status = $data['status'];
        $booking->save();

        Flash::success('Booking note updated.');

        return Backend::redirect(sprintf('cargie/booking/calendar'));
    }
}
