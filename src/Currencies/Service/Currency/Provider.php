<?php

declare(strict_types=1);

namespace Currencies\Service\Currency;

use Currencies\Data\Currency;
use Currencies\Exception\CurrencyNotFound;
use Currencies\Service\Client\ClientInterface;
use Currencies\Service\Transformer\CurrencyTransformer;
use Currencies\Service\Transformer\CurrencyTransformerInterface;

class Provider implements ProviderInterface
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var CurrencyTransformerInterface
     */
    private $transformer;

    public function __construct(
        ClientInterface $client,
        CurrencyTransformerInterface $transformer
    ) {
        $this->client = $client;
        $this->transformer = $transformer;
    }

    /**
     * @return Currency[]
     *
     * @throws CurrencyNotFound
     */
    public function getCurrencies(\DateTime $dateTime): array {
        $currencies = $this->client->getCurrencies($dateTime);

        return $this->transformer->transform($currencies);
    }
}