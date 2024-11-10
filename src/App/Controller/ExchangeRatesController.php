<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ExchangeRatesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ExchangeRatesController extends AbstractController
{
    /**
     * @var ExchangeRatesService
     */
    private $exchangeRatesService;
    /**
     * @var CacheInterface
     */
    private $cache;

    public function __construct(
        ExchangeRatesService $exchangeRatesService,
        CacheInterface $cache
    ) {
        $this->exchangeRatesService = $exchangeRatesService;
        $this->cache = $cache;
    }

    public function index(Request $request): Response
    {
        $date = $request->get('date');

        $cacheKey = 'currencies-' . $date; //for debug - add ". rand(0, PHP_INT_MAX);" to bypass cache
        $responseContent = $this->cache->get($cacheKey, function (ItemInterface $item) use ($date): string {
            $timezone = new \DateTimeZone('Europe/Warsaw');
            $now = new \DateTime('now', $timezone);
            $expiryTime = new \DateTime('12:00', $timezone);

            if ($now >= $expiryTime) {
                $expiryTime->modify('+1 day');
            }

            $item->expiresAt($expiryTime);

            $dateTime = new \DateTimeImmutable($date);

            $response = $this->exchangeRatesService->getCurrencyRates($dateTime);

            return json_encode($response);
        });

        return new Response(
            $responseContent,
            Response::HTTP_OK,
            ['Content-type' => 'application/json']
        );
    }
}
