<?php

namespace GiocoPlus\FilterDateRangePicker;

use GiocoPlus\Admin\Admin;
use Illuminate\Support\ServiceProvider;

class FilterDateRangePickerServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(FilterDateRangePickerExtension $extension)
    {
        if (! FilterDateRangePickerExtension::boot()) {
            return ;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'laravel-admin-filterdaterangepicker');
        }

        $this->registerPublishing();

        Admin::booting(function () {
            Admin::js('vendor/laravel-admin-ext/filterdaterangepicker/moment.min.js');
            Admin::js('vendor/laravel-admin-ext/filterdaterangepicker/daterangepicker.js');
            Admin::css('vendor/laravel-admin-ext/filterdaterangepicker/daterangepicker.css');
        });
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/../resources/lang' => resource_path('lang')], 'laravel-admin-filterdaterangepicker');
            $this->publishes([__DIR__.'/../resources/assets' => public_path('vendor/laravel-admin-ext/filterdaterangepicker')], 'laravel-admin-filterdaterangepicker');
        }
    }
}
