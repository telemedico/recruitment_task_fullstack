<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\NBP\ExchangeRates\RequestDTO;
use App\Service\NBP\ExchangeRate\GetServiceInterface;
use DateTime;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;

class ExchangeRatesController extends AbstractController
{
    /** @var GetServiceInterface */
    private $getService;

    /** @var ParameterBagInterface */
    private $parameterBag;

    /** @var DateTime */
    private $configDateFrom;

    public function __construct(
        GetServiceInterface   $getService,
        ParameterBagInterface $parameterBag
    )
    {
        $this->getService = $getService;
        $this->parameterBag = $parameterBag;
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @throws InvalidArgumentException
     */
    public function index(Request $request): Response
    {
        try {
            $exchangeRatesDTO = $this->getService
                ->getExchangeRateDTOByRequestDTO(
                    $this->prepareRequestDTO($request)
                )
                ->setRatesDateFrom($this->configDateFrom);

            return new Response(
                json_encode($exchangeRatesDTO),
                Response::HTTP_OK,
                ['Content-type' => 'application/json']
            );
        } catch (Throwable $exception) {
            return new Response(
                json_encode(['message' => $exception->getMessage()]),
                !empty($exception->getCode())
                    ? $exception->getCode()
                    : Response::HTTP_INTERNAL_SERVER_ERROR,
                ['Content-type' => 'application/json']
            );
        }
    }

    /**
     * @param Request $request
     *
     * @return RequestDTO
     *
     * @throws UnprocessableEntityHttpException
     */
    private function prepareRequestDTO(Request $request): RequestDTO
    {
        return (new RequestDTO())
            ->setDate($this->getDateQueryParam($request));
    }

    /**
     * @param Request $request
     *
     * @return DateTime
     *
     * @throws UnprocessableEntityHttpException
     */
    private function getDateQueryParam(Request $request): DateTime
    {
        $date = $request->query->get('date');

        $this->configDateFrom = DateTime::createFromFormat(
            RequestDTO::DATE_FORMAT,
            $this->parameterBag->get('nbp')['exchangeRates']['fromDate']
        );

        if (!$date) {
            return new DateTime();
        }

        $date = DateTime::createFromFormat(RequestDTO::DATE_FORMAT, $date);

        if (!$date) {
            throw new UnprocessableEntityHttpException(
                'Invalid date format',
                null,
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if ($date > (new DateTime())) {
            throw new UnprocessableEntityHttpException(
                'The date cannot be later than today',
                null,
                Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($date < $this->configDateFrom) {
            throw new UnprocessableEntityHttpException(
                sprintf(
                    'The date cannot be earlier than %s',
                    $this->configDateFrom->format(RequestDTO::DATE_FORMAT)
                ),
                null,
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return $date;
    }
}
