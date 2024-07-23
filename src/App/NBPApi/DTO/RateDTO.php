<?php
declare(strict_types=1);

namespace App\NBPApi\DTO;

use App\Utils\ArrayHelper;

class RateDTO
{
    /** @var string */
    public $currency;

    /** @var string */
    public $code;

    /** @var float */
    public $mid;

    /** @var float */
    public $sell;

    /** @var float */
    public $buy;

    public function __construct(string $currency, string $code, float $mid, float $sell, float $buy)
    {
        $this->currency = $currency;
        $this->code = $code;
        $this->mid = $mid;
        $this->sell = $sell;
        $this->buy = $buy;
    }

    /**
     * @param array $rates
     * @return RateDTO[]
     */
    public static function fromArray(array $rates, array $selectedRates): array
    {
        $result = [];

        foreach ($rates as $rate) {
            $code = ArrayHelper::get($rate, 'code', '');
            if (empty($selectedRates) || in_array($code, $selectedRates)) {
                $result[] = new self(
                    ArrayHelper::get($rate, 'currency', ''),
                    $code,
                    ArrayHelper::get($rate, 'mid', ''),
                    0,
                    0
                );
            }
        }

        return $result;
    }
}
