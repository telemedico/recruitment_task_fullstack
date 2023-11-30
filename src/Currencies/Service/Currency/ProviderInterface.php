<?php

declare(strict_types=1);

namespace Currencies\Service\Currency;

use Currencies\Data\Currency;

interface ProviderInterface
{

    /**
     * @return Currency[]
     */
    public function getCurrencies(\DateTime $dateTime): array;
}