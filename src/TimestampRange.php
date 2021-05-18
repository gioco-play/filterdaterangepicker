<?php

namespace GiocoPlus\FilterDateRangePicker;

use GiocoPlus\Admin\Grid\Filter\AbstractFilter;
use GiocoPlus\FilterDateRangePicker\Presenter\FilterDaterangePicker;
use Illuminate\Support\Arr;

class TimestampRange extends AbstractFilter {


    /**
     * Get condition of this filter.
     *
     * @param array $inputs
     *
     * @return mixed
     */
    public function condition($inputs)
    {
        if (!Arr::has($inputs, $this->column)) {
            return;
        }

        preg_match_all("/\d{4}-\d{2}-\d{2}[\s]\d{2}:\d{2}:\d{2}/i", Arr::get($inputs, $this->column), $out);


        $this->value['start'] = $out[0][0] ?? date('Y-m-d h:i:s');
        $this->value['end'] = $out[0][1] ?? date('Y-m-d h:i:s');

        $value = array_filter($this->value, function ($val) {
            return $val !== '';
        });

        if (empty($value)) {
            return;
        }

        $value['start'] = isset($value['start']) ? date_to_utc($value['start'])."000" : null;
        $value['end'] = isset($value['end']) ? date_to_utc($value['end'])."999" : null;

        if (!isset($value['start'])) {
            return $this->buildCondition($this->column, '<=', $value['end']);
        }

        if (!isset($value['end'])) {
            return $this->buildCondition($this->column, '>=', $value['start']);
        }

        $this->query = 'whereBetween';
        # 轉為 int
        $this->value = array_map(function($v){
            return intval($v);
        }, array_values($value));

        return $this->buildCondition($this->column, $this->value);
    }

    /**
     * 時間範圍選擇器
     *
     * @return void
     */
    public function daterangepicker($options = []) {

        $_options = [
            'maxDate' => date('Y-m-d h:i:s',strtotime('+1 day')),
            "maxSpan" => [
                "days" => 7
            ],
            'ranges' => [
                trans('filterdaterangepicker.today') => [date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')],
                trans('filterdaterangepicker.yesterday') => [date('Y-m-d 00:00:00',strtotime('-1 day')), date('Y-m-d 23:59:59',strtotime('-1 day'))],
                trans('filterdaterangepicker.3days') => [date('Y-m-d 00:00:00',strtotime('-3 day')), date('Y-m-d 23:59:59')],
                trans('filterdaterangepicker.7days') => [date('Y-m-d 00:00:00',strtotime('-7 day')), date('Y-m-d 23:59:59')]
            ],
            'locale' => [
                'applyLabel' => trans('filterdaterangepicker.apply'),
                'cancelLabel' => trans('filterdaterangepicker.cancel'),
                'fromLabel' => trans('filterdaterangepicker.from'),
                'toLabel' => trans('filterdaterangepicker.to'),
                'customRangeLabel' => trans('filterdaterangepicker.customRange'),
                'daysOfWeek' => trans('filterdaterangepicker.daysOfWeek'),
                'monthNames' => trans('filterdaterangepicker.monthNames'),
                'firstDay' => 1
            ]
        ];

        $options = array_merge_recursive_distinct($_options, $options);

        return $this->setPresenter(new FilterDaterangePicker($options));
    }

}

?>
