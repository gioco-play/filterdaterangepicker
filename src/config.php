<?php

return [
    /**
     *  method:day,week,month
     *  start
     *  end
     *  format
     */

    'default' => [
        'startDate' => [
            'method' => 'day',
            'start' => 0
        ],
        'endDate' => [
            'method' => 'day',
            'end' => 0
        ],
        'maxDate' => [
            'method' => 'day',
            'end' => 0
        ],
        "maxSpan" => [
            "days" => 7
        ],
        'ranges' => [
            'filterdaterangepicker.today' => [
                'method' => 'day',
                'start' => 0,
                'end' => 0
            ],
            'filterdaterangepicker.yesterday' => [
                'method' => 'day',
                'start' => -1,
                'end' => -1
            ],
            'filterdaterangepicker.3days' => [
                'method' => 'day',
                'start' => -2,
                'end' => 0
            ],
            'filterdaterangepicker.7days' =>[
                'method' => 'day',
                'start' => -6,
                'end' => 0
            ]
        ],
        'locale' => [
            'applyLabel' => 'filterdaterangepicker.apply',
            'cancelLabel' => 'filterdaterangepicker.cancel',
            'fromLabel' => 'filterdaterangepicker.from',
            'toLabel' => 'filterdaterangepicker.to',
            'customRangeLabel' => 'filterdaterangepicker.customRange',
            'daysOfWeek' => 'filterdaterangepicker.daysOfWeek',
            'monthNames' => 'filterdaterangepicker.monthNames',
            'firstDay' => 1
        ]
    ],
    
];
