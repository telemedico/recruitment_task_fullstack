<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\NBP\ExchangeRate\GetServiceInterface;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExchangeRatesController extends AbstractController
{
    /** @var GetServiceInterface */
    private $getService;

    public function __construct(
        GetServiceInterface $getService
    )
    {
        $this->getService = $getService;
    }

    public function index(Request $request): Response
    {
        // ToDo :: validate & get date to DateTime

        try {
            $exchangeRatesDTO = $this->getService->getExchangeRateDTOByDate(new DateTime());
        } catch (NotFoundHttpException $exception) {
            return new Response($exception->getMessage(), Response::HTTP_NOT_FOUND);
        }

        return new Response(
            '', // ToDo :: $exchangeRatesDTO->toJson()
            Response::HTTP_OK,
            ['Content-type' => 'application/json']
        );
    }
}
