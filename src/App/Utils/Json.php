<?php
declare(strict_types=1);

namespace App\Utils;

class Json
{
    public static function decode($value): array
    {
        return json_decode($value, true);
    }
}
