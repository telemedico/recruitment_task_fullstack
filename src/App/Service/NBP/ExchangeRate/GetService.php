<?php

namespace App\Service\NBP\ExchangeRate;

use App\DTO\NBP\ExchangeRatesDTO;
use App\Repository\API\NBP\ExchangeRateRepositoryInterface;
use App\Service\NBP\ExchangeRate\DTO\FactoryServiceInterface;
use DateTime;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetService implements GetServiceInterface
{
    /** @var CacheServiceInterface */
    private $cacheService;
    /** @var ExchangeRateRepositoryInterface */
    private $exchangeRateRepository;
    /** @var FactoryServiceInterface */
    private $factoryService;

    public function __construct(
        CacheServiceInterface           $cacheService,
        ExchangeRateRepositoryInterface $exchangeRateRepository,
        FactoryServiceInterface         $factoryService
    )
    {
        $this->cacheService = $cacheService;
        $this->exchangeRateRepository = $exchangeRateRepository;
        $this->factoryService = $factoryService;
    }

    /** {@inheritDoc} */
    public function getExchangeRateDTOByDate(DateTime $date): ExchangeRatesDTO
    {
        $exchangeRatesDTO = $this->cacheService->getCachedExchangeRatesDTOByDate($date);

        if ($exchangeRatesDTO) {
            return $exchangeRatesDTO;
        }

        $exchangeRatesDTO = $this->prepareExchangeRateDTO($date);

        $this->cacheService->setCacheByExchangeRatesDTO($exchangeRatesDTO);

        return $exchangeRatesDTO;
    }

    /**
     * @param DateTime $date
     *
     * @return ExchangeRatesDTO
     *
     * @throws NotFoundHttpException
     */
    private function prepareExchangeRateDTO(DateTime $date): ExchangeRatesDTO
    {
        $apiNBPData = $this->exchangeRateRepository->getRatesByTableAndDate($date);

        if (!$apiNBPData) {
            throw new NotFoundHttpException();
        }

        return $this->factoryService->createExchangeRatesDTOByResponseDataAndDate($apiNBPData, $date);
    }
}