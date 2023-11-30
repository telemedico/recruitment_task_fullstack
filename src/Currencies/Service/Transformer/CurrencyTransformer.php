<?php

declare(strict_types=1);

namespace Currencies\Service\Transformer;

use Currencies\Data\Currency;
use Currencies\Data\Currency\Rates;
use Currencies\Exception\CurrencyNotFound;
use Currencies\Service\Client\Response\CurrencyInterface as CurrencyResponseInterface;
use Currencies\Service\Currency\Calculator;

class CurrencyTransformer implements CurrencyTransformerInterface
{
    /**
     * @var []
     */
    private $config;

    public function __construct(array $currencyRates) {
        $this->config = $currencyRates;
    }

    public function transform(array $currencyResponse): array {
        $transformed = [];

        /** @var CurrencyResponseInterface $response */
        foreach ($currencyResponse as $response) {
            $icon = $this->getConfig($response->getCode())['icon'];

            $transformed[$response->getCode()] = new Currency(
                $response->getName(),
                $response->getCode(),
                $icon,
                $this->getRates($response)
            );
        }

        return $transformed;
    }

    private function getRates(CurrencyResponseInterface $currencyResponse): Rates {
        $config = $this->getConfig($currencyResponse->getCode());
        $calculator = $this->getCalculator($config);

        $value = $currencyResponse->getValue();
        $calculator->getSellPrice($value);

        return new Rates(
            $this->format($value, $config['digitsAfterDecimal']),
            $this->format($calculator->getSellPrice($value), $config['digitsAfterDecimal']),
            $this->format($calculator->getBuyPrice($value), $config['digitsAfterDecimal'])
        );
    }

    private function getCalculator(array $config): Calculator {
        return new Calculator($config);
    }

    private function getConfig(string $code): array {
        if (!isset($this->config[$code])) {
            throw new CurrencyNotFound('Currency code "' . $code . '" not supported');
        }

        return $this->config[$code];
    }

    private function format(?float $value, $digitsAfterDecimal): ?float {
        if (is_null($value)) {
            return null;
        }

        $formula = '%0.' . $digitsAfterDecimal . 'f';

        return (float)sprintf($formula, $value);
    }
}