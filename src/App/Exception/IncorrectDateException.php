<?php

declare(strict_types = 1);

namespace App\Exception;

class IncorrectDateException extends \Exception
{
    protected $message = 'Given date is out of range';
}