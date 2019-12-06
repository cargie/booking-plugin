<?php namespace Cargie\Booking\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Backend\Facades\Backend;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use October\Rain\Support\Facades\Flash;
use Responsiv\Currency\Models\Currency;

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

    public function index()
    {
        $this->asExtension('ListController')->index();
    }

    public function preview($recordId = null, $context = null)
    {
        $this->vars['currency'] = Currency::getPrimary();
        $this->bodyClass = 'slim-container';
        return $this->asExtension('FormController')->preview($recordId, $context);
    }

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Cargie.Booking', 'booking', 'bookings');
    }

    public function formAfterSave($model)
    {
        // $model->dates()->delete();
        // $from = Carbon::parse(post('Booking.from'));
        // $to = Carbon::parse(post('Booking.to'));
        // while ($from->lte($to)) {
        //     $model->dates()->create([
        //         'date' => $from->format('Y-m-d'),
        //     ]);
        //     $from->addDay();
        // }

        $invoice = $model->invoice()->create([
            'user_id' => $model->user_id
        ]);
        $invoice->first_name = $model->customer->name;
        $invoice->last_name = $model->customer->surname;
        $invoice->email = $model->customer->email;
        $invoice->phone = $model->customer->phone;

        $invoice->save();

        foreach($model->rooms as $room) {
            $item = $invoice->items()->create([
                'description' => $room->name,
                'quantity' => 1,
                'price' => $room->getCalculatedRate(),
            ]);
            $item->save();
        }

        $invoice->save();
    }

    public function formAfterDelete($model)
    {
        $model->dates()->delete();
    }

    public function preview_onLoadChangeStatusForm($recordId = null)
    {
        try {
            $booking = $this->formFindModelObject($recordId);
            $this->vars['widget'] = $this->makeStatusFormWidget($booking);
        }
        catch (Exception $ex) {
            $this->handleError($ex);
        }

        return $this->makePartial('change_status_form');
    }

    public function preview_onLoadChangePaymentStatusForm($recordId = null)
    {
        try {
            $booking = $this->formFindModelObject($recordId);
            $this->vars['widget'] = $this->makePaymentStatusFormWidget($booking);
        }
        catch (Exception $ex) {
            $this->handleError($ex);
        }

        return $this->makePartial('change_payment_status_form');
    }

    public function preview_onLoadChangeNoteForm($recordId)
    {
        try {
            $booking = $this->formFindModelObject($recordId);
            $this->vars['widget'] = $this->makeNoteFormWidget($booking);
        } catch (Exception $ex) {
            $this->handleError($ex);
        }

        return $this->makePartial('change_note_form');
    }

    public function preview_onChangeStatus($recordId = null)
    {
        $booking = $this->formFindModelObject($recordId);

        $widget = $this->makeStatusFormWidget($booking);

        $data = $widget->getSaveData();

        $booking->status = $data['status'];
        $booking->save();

        Flash::success('Booking status updated.');

        return Backend::redirect(sprintf('cargie/booking/bookings/preview/%s', $booking->id));
    }

    public function preview_onChangePaymentStatus($recordId = null)
    {
        $booking = $this->formFindModelObject($recordId);

        $widget = $this->makePaymentStatusFormWidget($booking);

        $data = $widget->getSaveData();

        $booking->payment_status = $data['payment_status'];
        $booking->save();

        Flash::success('Booking payment status updated.');

        return Backend::redirect(sprintf('cargie/booking/bookings/preview/%s', $booking->id));
    }

    public function preview_onChangeNote($recordId = null)
    {
        $booking = $this->formFindModelObject($recordId);

        $widget = $this->makeNoteFormWidget($booking);

        $data = $widget->getSaveData();
        $booking->note = $data['note'];
        $booking->save();

        Flash::success('Booking note updated.');

        return Backend::redirect(sprintf('cargie/booking/bookings/preview/%s', $booking->id));
    }

    protected function makeStatusFormWidget($booking)
    {
        $model = $booking;

        $config = $this->makeConfig('~/plugins/cargie/booking/models/booking/update_status_fields.yaml');
        $config->model = $model;

        return $this->makeWidget('Backend\Widgets\Form', $config);
    }

    protected function makePaymentStatusFormWidget($booking)
    {
        $model = $booking;

        $config = $this->makeConfig('~/plugins/cargie/booking/models/booking/update_payment_status_fields.yaml');
        $config->model = $model;

        return $this->makeWidget('Backend\Widgets\Form', $config);
    }

    protected function makeNoteFormWidget($booking)
    {
        $model = $booking;

        $config = $this->makeConfig('~/plugins/cargie/booking/models/booking/update_note_fields.yaml');
        $config->model = $model;

        return $this->makeWidget('Backend\Widgets\Form', $config);
    }
}
