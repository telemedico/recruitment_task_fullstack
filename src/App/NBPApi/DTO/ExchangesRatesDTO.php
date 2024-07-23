<?php
declare(strict_types=1);

namespace App\NBPApi\DTO;

use App\Utils\ArrayHelper;

class ExchangesRatesDTO
{
    /** @var string  */
    public $no;

    /** @var string  */
    public $date;

    /** @var RateDTO[]  */
    public $rates = [];

    public function __construct(string $no, string $date, array $rates, array $selectedRates = [])
    {
        $this->no = $no;
        $this->date = $date;
        $this->rates = RateDTO::fromArray($rates, $selectedRates);
    }

    /**
     * @param array $response
     * @return self
     */
    public static function fromResponse(array $response, array $selectedRates = []): self
    {
        return new self(
            ArrayHelper::get($response, '0.no', ''),
            ArrayHelper::get($response, '0.effectiveDate', ''),
            ArrayHelper::get($response, '0.rates', ''),
            $selectedRates
        );
    }
}
