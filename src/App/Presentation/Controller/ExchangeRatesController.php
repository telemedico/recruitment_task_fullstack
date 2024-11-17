<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Domain\Query\FetchExchangeRatesQueryInterface;
use App\Domain\Query\Filter\ExchangeRatesFilter;
use App\Presentation\Controller\Request\ExchangeRatesRequest;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class ExchangeRatesController extends AbstractController
{
    /**
     * @var FetchExchangeRatesQueryInterface
     */
    private $query;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        FetchExchangeRatesQueryInterface $query,
        LoggerInterface $logger
    ) {
        $this->query = $query;
        $this->logger = $logger;
    }

    public function __invoke(ExchangeRatesRequest $request): Response
    {
        try {
            return $this->json($this->query->query(new ExchangeRatesFilter(
                $request->getUserDate(),
                $request->getLatestDate()
            )));
        } catch (\Throwable $e) {
            $this->logger->error('Exchange rates request failed.', ['exception' => $e]);

            return $this->json(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
