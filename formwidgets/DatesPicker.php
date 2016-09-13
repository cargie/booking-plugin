<?php namespace Cargie\Booking\FormWidgets;

use Backend\Classes\FormWidgetBase;
use Carbon\Carbon;

/**
 * DatePicker Form Widget
 */
class DatesPicker extends FormWidgetBase
{
    use \Backend\Traits\FormModelWidget;

    public $minYear = null;
    public $maxYear = null;
    public $format = 'Y-m-d';
    public $mode = 'relation';
    /**
     * {@inheritDoc}
     */
    protected $defaultAlias = 'cargie_booking_date_picker';

    /**
     * {@inheritDoc}
     */
    public function init()
    {
        $this->fillFromConfig([
            'format',
            'minYear',
            'maxYear',
            'relation',
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function render()
    {
        $this->prepareVars();
        return $this->makePartial('datespicker');
    }

    /**
     * Prepares the form widget view data
     */
    public function prepareVars()
    {
        $this->vars['name'] = $this->formField->getName();
        $this->vars['value'] = $this->getLoadValue();
        $this->vars['year_now'] = Carbon::now()->year;
        $this->vars['relation'] = $this->relation;
        $this->vars['maxYear'] = $this->maxYear;
        $this->vars['minYear'] = $this->minYear;
    }

    /**
     * {@inheritDoc}
     */
    public function loadAssets()
    {
        $this->addCss('css/datespicker.css', 'Cargie.Booking');
        $this->addJs('js/datespicker.js', 'Cargie.Booking');
    }

    /**
     * {@inheritDoc}
     */
    public function getSaveValue($value)
    {
        $relation = $this->getRelationModel();
        // $dates = collect();
        // foreach ($value as $date) {
        //     $this->model->dates()->create([
        //         'date' => $date,
        //         'season_id' => 3,
        //     ]);
        // }
        return $value;
    }

    public function getLoadValue()
    {
        // if ($this->mode === "relation") {
        return ($this->model->dates->lists('date'));
        // }
    }

}
