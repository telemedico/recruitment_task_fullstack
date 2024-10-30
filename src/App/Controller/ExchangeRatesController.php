<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\IncorrectDateException;
use App\ExchangeRate\ApiResponse;
use App\ExchangeRate\CurrencyExchangeClientFactory;
use DateTime;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ExchangeRatesController extends AbstractController
{
    /**
     * @var CurrencyExchangeClientFactory
     */
    private $exchangeRateFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        CurrencyExchangeClientFactory $exchangeRateFactory,
        LoggerInterface $logger
    ) {
        $this->exchangeRateFactory = $exchangeRateFactory;
        $this->logger = $logger;
    }

    public function getRates(string $date): ApiResponse
    {
        try {
            $date = new DateTime($date);
            $response = $this->generateResponse($date);

            return new ApiResponse($response);
        } catch (IncorrectDateException $e) {
            return new ApiResponse(
                null,
                "Can't get rates: {$e->getMessage()}",
                Response::HTTP_BAD_REQUEST
            );
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());

            return new ApiResponse(
                null,
                "Can't get rates, please try again later.",
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    private function generateResponse(DateTime $date): array
    {
        $result = [];

        $exchangeRate = $this->exchangeRateFactory->create();
        $exchangeRate->setDate($date);

        foreach ($exchangeRate->getRates() as $rate) {
            $result[$rate->getCurrency()] = [
                'rate' => $rate->getRate(),
                'buyRate' => $rate->getBuyingRate(),
                'sellRate' => $rate->getSellingRate()
            ];
        }

        return $result;
    }


}
