<?php

namespace GiocoPlus\FilterDateRangePicker;

use GiocoPlus\Admin\Grid\Filter\AbstractFilter;
use GiocoPlus\FilterDateRangePicker\Presenter\FilterDaterangePicker;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class TimestampRange extends AbstractFilter {

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

        preg_match_all("/\d{4}-\d{2}-\d{2}[\s]\d{2}:\d{2}:\d{2}/i", Arr::get($inputs, $this->column), $out);


        $this->value['start'] = $out[0][0] ?? date('Y-m-d h:i:s');
        $this->value['end'] = $out[0][1] ?? date('Y-m-d h:i:s');

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

    /**
     * 時間範圍選擇器
     *
     * @return void
     */
    public function daterangepicker($conf = 'default', $options = []) {

        $config = config("daterangepicker.{$conf}");
        if (request()->has($this->column)){
                unset($config["startDate"], $config["endDate"]);
        }
        $_options = $this->fmt($config);

        $options = array_merge_recursive_distinct($_options, $options);

        return $this->setPresenter(new FilterDaterangePicker($options));
    }

    /**
     * Option格式化
     * @param array $config
     * @return void
     */
    private function fmt($config = [])
    {
        foreach ($config as $key => $value) {

            switch ($key) {
                case 'minDate':
                case 'startDate':
                    $config[$key] = $this->transDateTime($config[$key]['method'], $config[$key]['start'], 'start')->format("Y-m-d H:i:s");
                    break;
                case 'maxDate':
                case 'endDate':
                    $config[$key] = $this->transDateTime($config[$key]['method'], $config[$key]['end'], 'end')->format("Y-m-d H:i:s");
                    break;
                case 'ranges':
                    $ranges = [];
                    foreach ($value as $k => $v) {
                        $format = $v['format'] ?? "Y-m-d H:i:s";
                        $ranges[trans($k)][] =  $this->transDateTime($v['method'], $v['start'], 'start')->format($format);
                        $ranges[trans($k)][] =  $this->transDateTime($v['method'], $v['end'], 'end')->format($format);
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

    /**
     * 時區轉換
     * @param $method
     * @param $during
     * @param $near
     * @return Carbon
     */
    private function transDateTime($method, $during, $near){
        $now = Carbon::now(date_default_timezone_get())->timezone($this->timezone);
        switch ($method) {
            case 'day':
                $now = $now->addDays($during);
                $now = $near == 'end' ? $now->endOfDay() : $now->startOfDay() ;
                break;
            case 'week':
                $now = $now->addWeeks($during);
                $now = $near == 'end' ? $now->endOfWeek() : $now->startofWeek() ;
                break;
            case 'month':
                $now = $now->addMonths($during);
                $now = $near == 'end' ? $now->endOfMonth() : $now->startOfMonth() ;
                break;
        }

        return $now;
    }

}

?>
