<?php

declare(strict_types=1);

namespace App\Domain\Query\View;

final class ExchangeRateView implements \JsonSerializable
{
    /**
     * @var string
     */
    private $currencyCode;

    /**
     * @var string
     */
    private $currencyName;

    /**
     * @var float|null
     */
    private $latestBidRate;

    /**
     * @var float|null
     */
    private $latestAskRate;

    /**
     * @var float|null
     */
    private $latestNbpRate;

    /**
     * @var float|null
     */
    private $userDateBidRate;

    /**
     * @var float|null
     */
    private $userDateAskRate;

    /**
     * @var float|null
     */
    private $userDateNbpRate;

    private function __construct(
        string $currencyCode,
        string $currencyName,
        ?float $latestBidRate,
        ?float $latestAskRate,
        ?float $latestNbpRate,
        ?float $userDateBidRate,
        ?float $userDateAskRate,
        ?float $userDateNbpRate
    ) {
        $this->currencyCode = $currencyCode;
        $this->currencyName = $currencyName;
        $this->latestBidRate = $latestBidRate;
        $this->latestAskRate = $latestAskRate;
        $this->latestNbpRate = $latestNbpRate;
        $this->userDateBidRate = $userDateBidRate;
        $this->userDateAskRate = $userDateAskRate;
        $this->userDateNbpRate = $userDateNbpRate;
    }

    public function jsonSerialize(): array
    {
        return \get_object_vars($this);
    }

    public static function fromArray(array $array): self
    {
        return new self(
            $array['currencyCode'],
            $array['currencyName'],
            $array['latestBidRate'] ?? null,
            $array['latestAskRate'] ?? null,
            $array['latestNbpRate'] ?? null,
            $array['userDateBidRate'] ?? null,
            $array['userDateAskRate'] ?? null,
            $array['userDateNbpRate'] ?? null
        );
    }
}
