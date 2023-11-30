<?php

declare(strict_types=1);

namespace Currencies\Service\Client;

interface ClientInterface
{
    public function getCurrencies(\DateTime $dateTime): array;
}