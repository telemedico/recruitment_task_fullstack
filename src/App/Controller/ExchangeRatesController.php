<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Service\Nbp;

class ExchangeRatesController extends AbstractController
{
    public function list(string $date): Response
    {
        return $this->render(
            'exchange_rates/currency-list.html.twig'
        );
    }

    public function getCurrencyData(Nbp $nbp, string $date): Response
    {
        if (!Nbp::isValidDate($date)) {
            return $this->json(['message' => 'error'], 404);
        }
        return $this->json(['items' => $nbp->getByDate($date)]);
    }
}
