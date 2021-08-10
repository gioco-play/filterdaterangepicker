<?php

namespace GiocoPlus\FilterDateRangePicker;

use GiocoPlus\Admin\Grid\Filter\AbstractFilter;
use GiocoPlus\FilterDateRangePicker\Presenter\FilterDaterangePicker;
use Illuminate\Support\Arr;

class DatetimeRange extends AbstractFilter {


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

        if (!isset($value['start'])) {
            return $this->buildCondition($this->column, '<=', $value['end']);
        }

        if (!isset($value['end'])) {
            return $this->buildCondition($this->column, '>=', $value['start']);
        }

        $this->query = 'whereBetween';

        return $this->buildCondition($this->column, $this->value);
    }

    /**
     * 時間範圍選擇器
     *
     * @return void
     */
    public function daterangepicker($options = []) {

        $_options = $this->trans(config('daterangepicker.datetime'));

        $options = array_merge_recursive_distinct($_options, $options);

        return $this->setPresenter(new FilterDaterangePicker($options));
    }

    /**
     * 翻譯config
     *
     * @param array $config
     * @return void
     */
    private function trans($config = [])
    {
        foreach ($config as $key => $value) {

            switch ($key) {
                case 'ranges':
                    $ranges = [];
                    foreach ($value as $k => $v) {
                        $ranges[trans($k)] = $v;
                    }
                    $config['ranges'] = $ranges;
                    break;
                case 'locale':
                    foreach ($value as $k =>$v) {
                        $value[$k] = trans($v);
                    }
                    $config['locale'] = $value;
                    break;
            }
        }
        return $config;
    }

}

?>
