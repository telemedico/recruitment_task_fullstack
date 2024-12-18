<?php

namespace App\Controller;

use App\Service\ExchangeRate\NbpApiProvider;
use App\Service\ExchangeRate\ExchangeRateCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ExchangeRatesController extends AbstractController
{
    private $nbpProvider;
    private $calculator;
    private $logPath;

    public function __construct(NbpApiProvider $nbpProvider, ExchangeRateCalculator $calculator)
    {
        $this->nbpProvider = $nbpProvider;
        $this->calculator = $calculator;
        $this->logPath = __DIR__ . '/../../../var/log/exchange.log';
    }

    /**
     * @Route("/api/exchange-rates", name="api_exchange_rates", methods={"GET"})
     */
    public function getExchangeRates(Request $request): JsonResponse
    {
        try {
            $requestedDate = $request->query->get('date');
            file_put_contents($this->logPath, "Requested date: " . $requestedDate . "\n", FILE_APPEND);

            if ($requestedDate) {
                $date = \DateTime::createFromFormat('Y-m-d', $requestedDate);
                if (!$date) {
                    return $this->json([
                        'error' => 'Invalid date format. Use YYYY-MM-DD'
                    ], 400);
                }
            } else {
                $date = new \DateTime();
            }

            $historicalRates = $this->nbpProvider->getRatesForDate($date);

            $today = new \DateTime();
            $currentRates = $this->nbpProvider->getRatesForDate($today);

            $historicalCalculated = array_map(
                [$this->calculator, 'calculateRate'],
                $historicalRates['rates']
            );

            $currentCalculated = array_map(
                [$this->calculator, 'calculateRate'],
                $currentRates['rates']
            );

            $response = [
                'historical' => $historicalCalculated,
                'current' => $currentCalculated,
                'date' => $historicalRates['effectiveDate'],
                'today' => $currentRates['effectiveDate']
            ];

            file_put_contents($this->logPath, "Response: " . json_encode($response) . "\n", FILE_APPEND);

            return $this->json($response);
        } catch (\Exception $e) {
            file_put_contents($this->logPath, "Error: " . $e->getMessage() . "\n", FILE_APPEND);
            return $this->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
