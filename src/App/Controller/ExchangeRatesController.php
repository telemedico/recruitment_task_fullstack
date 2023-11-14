<?php

declare(strict_types=1);

namespace App\Controller;

use App\Services\ExchangeRatesService;
use DateInterval;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ExchangeRatesController extends AbstractController
{

    /**
     * @var ExchangeRatesService
     */
    private $exchangeRatesService;

    public function __construct(ExchangeRatesService $exchangeRatesService)
    {
        $this->exchangeRatesService = $exchangeRatesService;
    }

    public function tables(Request $request): Response
    {
        $dateParameter = $request->query->get('date');
        $today = (new DateTime())->setTime(0, 0);
        $date = DateTime::createFromFormat('Y-m-d', $dateParameter)->setTime(0,0);

        if (!$date || $date > new $today) {
            return $this->json(
                ['error' => 'Parametr "date" musi być poprawną datą i nie może być większy niż dzisiejsza data.'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        // NBP nie zwraca danych z dnia dzisiejszego a ni na podstawie daty a nie parametru today
        if ($today === $date) {
            $date = (new DateTime())->sub(new DateInterval('P1D'));
        }

        return $this->json($this->exchangeRatesService->getRates($date));
    }
}
