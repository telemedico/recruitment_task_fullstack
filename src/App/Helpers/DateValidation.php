<?php

namespace App\Helpers;

use DateTime;

class DateValidation
{   
    /**
     * Checks the correctness of the date format.
     *
     * @param string $date
     * @return bool
     */
    public static function checkIsValidFormatDateRule(string $date): bool
    {
        $d = DateTime::createFromFormat($_ENV['DATE_FORMAT'], $date);
        return $d && $d->format($_ENV['DATE_FORMAT']) == $date;
    }

    /**
     * Checks if the date is a weekend.
     *
     * @param string $date
     * @return bool
     */
    public static function checkIsNotWeekendRule(string $date): bool
    {
        return (date('N', strtotime($date)) < 6);
    }

    /**
     * Checks if the date is in the correct range.
     *
     * @param string $date
     * @return bool
     */
    public static function checkIsBetweenRule(string $date): bool
    {
        $now = date($_ENV['DATE_FORMAT']);
        $date = date($_ENV['DATE_FORMAT'], strtotime($date));
        $dateStart = date($_ENV['DATE_FORMAT'], strtotime($_ENV['BEGIN_DATE_EXCHANGE_RATES']));
        
        return (($date >= $dateStart) && ($date <= $now));
    }

    /**
     * Checks all date validity rules.
     *
     * @param string $date
     * @return array
     */
    public static function validateDate(string $date): array
    {
        $err = [];

        if (!self::checkIsValidFormatDateRule($date)) {
            $err[] = 'date format is incorrect.';
        }

        if (!self::checkIsNotWeekendRule($date)) {
            $err[] = 'date falls on a weekend. No exchange rates.';
        }

        if (!self::checkIsBetweenRule($date)) {
            $err[] = 'date is outside the supported range. No exchange rates.';
        }

        return $err;
    }

}