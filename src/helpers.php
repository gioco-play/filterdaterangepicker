<?php

if (!function_exists('datetime_to_local')) {

    /**
     * 日期時區轉換
     *
     * @param [type] $datetime
     * @param string $format
     * @param string $timezone
     * @return string
     */
    function datetime_to_local($datetime, $format = 'Y-m-d H:i:s', $timezone = 'Asia/Taipei'): string {
        $date = new \DateTime($datetime, new \DateTimeZone('UTC'));
        $date->setTimezone(new \DateTimeZone($timezone));

        return $date->format($format); // 2011-11-10 15:17:23 -0500
    }

}

if (!function_exists('timestamp_to_local')) {

    /**
     * 時間戳時區轉換
     *
     * @param [type] $timestamp
     * @param string $format
     * @param string $timezone
     * @return string
     */
    function timestamp_to_local($timestamp, $format = 'Y-m-d H:i:s', $timezone = 'Asia/Taipei'): string {
        $timestamp = substr($timestamp, 0, 10);
        $datestr = date("Y-m-d H:i:s", $timestamp);
        $date = new \DateTime($datestr, new \DateTimeZone('UTC'));
        $date->setTimezone(new \DateTimeZone($timezone));

        return $date->format($format); // 2011-11-10 15:17:23 -0500
    }

}

if (!function_exists('date_to_utc')) {

    /**
     * 時間戳時區轉換
     *
     * @param [type] $date
     * @param string $timezone
     * @return string
     */
    function date_to_utc($date, $timezone = 'Asia/Taipei'): string {
        $dt = new \DateTime($date, new \DateTimeZone($timezone));
        $dt->setTimezone(new \DateTimeZone("UTC"));
        return intval($dt->getTimestamp());
    }

}