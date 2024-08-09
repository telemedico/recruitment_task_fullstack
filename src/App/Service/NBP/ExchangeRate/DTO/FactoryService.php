<?php

namespace App\Service\NBP\ExchangeRate\DTO;

use App\DTO\NBP\ExchangeRates\DTO;
use App\DTO\NBP\ExchangeRates\RequestDTO;
use App\Service\NBP\ExchangeRate\DTO\Factories\FactoryInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class FactoryService implements FactoryServiceInterface
{
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;
    /**
     * @var iterable<FactoryInterface>
     */
    private $factories;

    public function __construct(
        ParameterBagInterface $parameterBag,
        iterable              $factories
    )
    {
        $this->parameterBag = $parameterBag;
        $this->factories = $factories;
    }

    /** {@inheritDoc} */
    public function createExchangeRatesDTO(
        array      $responseData,
        RequestDTO $requestDTO
    ): DTO
    {
        if (!isset($responseData[0]['rates'])) {
            throw new UnprocessableEntityHttpException(
                'Bad response data from NBP API',
                null,
                Response::HTTP_BAD_REQUEST
            );
        }

        $config = $this->parameterBag->get('nbp')['exchangeRates'];

        $exchangeRatesDTO = (new DTO())
            ->setSupportedCurrenciesConfig($config['supportedCurrencies'])
            ->setBuyableCurrenciesConfig($config['buyableCurrencies'])
            ->setDate($requestDTO->getDate());

        foreach ($responseData[0]['rates'] as $rateData) {
            foreach ($this->factories as $factory) {
                if (!$factory->isSupported($rateData, $exchangeRatesDTO)) {
                    continue;
                }

                $factory->appendCurrencyDTOToDTO($rateData, $exchangeRatesDTO);
            }
        }

        return $exchangeRatesDTO;
    }
}