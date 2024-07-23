<?php
declare(strict_types=1);

namespace App\NBPApi\Validator;

class Validator
{
    public static function validateDate(?string $date = null): bool
    {
        if (null === $date) {
            return true;
        }

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return false;
        }

        $dateTime = \DateTime::createFromFormat('Y-m-d', $date);
        $errors = \DateTime::getLastErrors();

        if ($errors['warning_count'] > 0 || $errors['error_count'] > 0) {
            return false;
        }

        $maxDate = new \DateTime('2023-01-02');

        if ($dateTime < $maxDate) {
            return false;
        }

        return true;
    }
}
