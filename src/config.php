<?php

return [

    'default' => [
        'maxDate' => date('Y-m-d H:i:s', strtotime('+0 day')),
        "maxSpan" => [
            "days" => 7
        ],
        'ranges' => [
            'filterdaterangepicker.today' => [date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')],
            'filterdaterangepicker.yesterday' => [date('Y-m-d 00:00:00',strtotime('-1 day')), date('Y-m-d 23:59:59',strtotime('-1 day'))],
            'filterdaterangepicker.3days' => [date('Y-m-d 00:00:00',strtotime('-3 day')), date('Y-m-d 23:59:59')],
            'filterdaterangepicker.7days' => [date('Y-m-d 00:00:00',strtotime('-7 day')), date('Y-m-d 23:59:59')]
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
    ]
    
];
