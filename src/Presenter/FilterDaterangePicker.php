<?php

namespace GiocoPlus\FilterDateRangePicker\Presenter;

use Encore\Admin\Grid\Filter\Presenter\Presenter;
use Encore\Admin\Facades\Admin;
use Illuminate\Support\Arr;

class FilterDaterangePicker extends Presenter
{
    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var string
     */
    protected $format = 'YYYY-MM-DD HH:mm:ss';

    /**
     * DateTime constructor.
     *
     * @param array $options
     */
    public function __construct($options = [])
    {
        $this->options = $this->getOptions($options);
    }

    /**
     * @see https://stackoverflow.com/questions/19901850/how-do-i-get-an-objects-unqualified-short-class-name
     *
     * @return string
     */
    public function view() : string
    {
        return 'laravel-admin-filterdaterangepicker::filterdaterangepicker';
    }

    /**
     * @param array $options
     *
     * @return mixed
     */
    protected function getOptions(array $options) : array
    {
        $_options = [
            'locale' => ['format' =>  Arr::get($options, 'format', $this->format)]
        ];
        $options = array_merge_recursive_distinct($_options, $options);
        return $options;
    }

    protected function prepare()
    {
        $script = "$('#{$this->filter->getId()}').daterangepicker(".json_encode($this->options).');';

        Admin::script($script);
    }

    public function variables() : array
    {
        $this->prepare();

        return [
            'group' => $this->filter->group,
        ];
    }
}
