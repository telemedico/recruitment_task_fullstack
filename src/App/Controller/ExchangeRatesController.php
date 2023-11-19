<?php

declare( strict_types=1 );

namespace App\Controller;

use App\Service\ExchangeRatesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use \DateTime;
use function json_encode;

/*
 * Nowy controller zwracajacy dane kursow walut
 */

class ExchangeRatesController extends AbstractController {

    /*
     * metoda zwracajaca kursy walut na podstawie podanej daty
     * metoda wykorzystuje ExchangeRatesService do otrzymania danych
     */
    public function index( Request $request, ExchangeRatesService $exchangeRatesService ): Response {
        $date = $request->get( 'date' );

        if ( ! empty( $date ) && DateTime::createFromFormat( 'Y-m-d', $date )->format( 'Y-m-d' ) === $date ) {
            $responseContent = $exchangeRatesService->get( $date );
        } else {
            $responseContent = $exchangeRatesService->get( date( 'Y-m-d' ) );
        }

        return new Response(
            json_encode( $responseContent ),
            Response::HTTP_OK,
            [ 'Content-type' => 'application/json' ]
        );
    }
}
