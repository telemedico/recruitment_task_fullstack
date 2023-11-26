<?php

namespace Unit\Helpers;

use App\Helpers\DateValidation;
use PHPUnit\Framework\TestCase;

class DateValidationTest extends TestCase
{
    /**
     * Test if the checkIsValidFormatDateRule method returns false with not correct date format.
     */
    public function testFormatDateWithInvalidValue(): void
    {
        $testValue = '2221-1111';
        $check = DateValidation::checkIsValidFormatDateRule($testValue);
        $this->assertFalse($check);
    }

    /**
     * Test if the checkIsValidFormatDateRule method returns true with correct date format.
     */
    public function testFormatDateWithValidValue(): void
    {
        $testValue = '2023-01-01';
        $check = DateValidation::checkIsValidFormatDateRule($testValue);
        $this->assertTrue($check);
    }

    /**
     * Test if the checkIsNotWeekendRule method returns false response with weekend.
     */
    public function testDateIsWeekDayWithSaturday(): void
    {
        $testValue = '2023-11-26';
        $check = DateValidation::checkIsNotWeekendRule($testValue);
        $this->assertFalse($check);
    }

    /**
     * Test if the checkIsNotWeekendRule method returns true response with work day.
     */
    public function testDateIsWorkDayWithFriday(): void
    {
        $testValue = '2023-11-24';
        $check = DateValidation::checkIsNotWeekendRule($testValue);
        $this->assertTrue($check);
    }

    /**
     * Test if the checkIsBetweenRule method returns false when the date is earlier than the supported date
     */
    public function testDateEarlierThanSupportedDate(): void
    {
        $dateStart = date($_ENV['DATE_FORMAT'], strtotime($_ENV['BEGIN_DATE_EXCHANGE_RATES']));
        $testValue = date($_ENV['DATE_FORMAT'], strtotime($_ENV['BEGIN_DATE_EXCHANGE_RATES'] . " -1 day"));
        $check = DateValidation::checkIsBetweenRule($testValue);
        $this->assertFalse($check);
    }

    /**
     * Test if the checkIsBetweenRule method returns false when the date is later than the supported date
     */
    public function testDateLaterThanSupportedDate(): void
    {
        $testValue = date($_ENV['DATE_FORMAT'], strtotime("+ 1 day"));
        $check = DateValidation::checkIsBetweenRule($testValue);
        $this->assertFalse($check);
    }

    /**
     * Test if the checkIsBetweenRule returns true when the date is supported
     */
    public function testDateIsSupported(): void
    {
        $testValue = date($_ENV['DATE_FORMAT'], strtotime($_ENV['BEGIN_DATE_EXCHANGE_RATES']));
        $check = DateValidation::checkIsBetweenRule($testValue);
        $this->assertTrue($check);
    }

    /**
     * Test if the validateDate returns not empty errors array with not valid date
     */
    public function testValidateDateWithNotValidDate(): void
    {
        $testValue = '2023-01-01';
        $check = DateValidation::validateDate($testValue);
        $this->assertNotEmpty($check);
    }
 
    /**
     * Test if the validateDate returns empty errors array with correct date
     */
    public function testValidateDateWithValidDate(): void
    {
        $testValue = '2023-01-02';
        $check = DateValidation::validateDate($testValue);
        $this->assertEmpty($check);
    }
}