<?php

namespace WsZurich\Wrapper;

class Helper
{
    /**
     * Date
     *
     * @param string $format
     * @param string $date
     * @param string $modify
     * @return string
     */
    public static function date($format, $date = null, $modify = null)
    {
        $date = new \DateTime($date);

        if (!is_null($modify)) {
            $date->modify($modify);
        }

        return $date->format($format);
    }

    /**
     * Interval Month Date
     *
     * @param mixed $startDate
     * @param mixed $endDate
     * @return int
     */
    public static function diffMonthsDate($startDate, $endDate)
    {
        $startDate = new \DateTime($startDate);
        $endDate   = new \DateTime($endDate);
        $interval  = $startDate->diff($endDate);

        return $interval->m;
    }
}  