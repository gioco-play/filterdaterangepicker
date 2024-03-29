<?php

namespace GiocoPlus\FilterDateRangePicker;

use GiocoPlus\Admin\Grid\Filter\Between;
use Illuminate\Support\Arr;

class TimestampBetween extends Between {

    protected $timezone;

    public function timezone($timezone) {
        $this->timezone = $timezone;
        return $this;
    }

    /**
     * Get condition of this filter.
     *
     * @param array $inputs
     *
     * @return mixed
     */
    public function condition($inputs)
    {
        $timezone = empty($this->timezone)?date_default_timezone_get():$this->timezone;

        if (!Arr::has($inputs, $this->column)) {
            return;
        }

        $this->value = Arr::get($inputs, $this->column);

        $value = array_filter($this->value, function ($val) {
            return $val !== '';
        });

        if (empty($value)) {
            return;
        }

        $value['start'] = isset($value['start']) ? date_to_utc($value['start'], $timezone)."000" : null;
        $value['end'] = isset($value['end']) ? date_to_utc($value['end'], $timezone)."999" : null;

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

}

?>
