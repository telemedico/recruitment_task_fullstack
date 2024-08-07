<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ExchangeRatesController extends AbstractController
{
    public function index(Request $request): Response
    {
        return new Response(
            [],
            Response::HTTP_OK,
            ['Content-type' => 'application/json']
        );
    }
}
