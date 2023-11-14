<?php

namespace App\Http\Nbp;

use DateTime;

interface NbpClient
{
    public function getTablesForDate(DateTime $date, string $tableName = 'A'): array;
}