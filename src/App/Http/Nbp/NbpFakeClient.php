<?php

namespace App\Http\Nbp;

use DateTime;

class NbpFakeClient implements NbpClient
{

    public function getTablesForDate(DateTime $date, string $tableName = 'A'): array
    {
       return [];
    }
}