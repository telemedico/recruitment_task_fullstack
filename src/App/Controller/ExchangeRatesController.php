<?php

declare(strict_types=1);

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Integration\Nbp\Client;

class ExchangeRatesController extends AbstractController
{
    private $nbpClient;

    public function __construct()
    {
        $this->nbpClient = new Client();
    }

    /**
     * @throws Exception
     */
    public function exchangeRate(Request $request): Response
    {
        return new Response(
            json_encode($this->nbpClient->getExchangeRates($request->get('currency'), $request->get('date'))),
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

}
