<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;


class DefaultController extends AbstractController
{

    public function index(): Response
    {
        return $this->render(
            'exchange_rates/app-root.html.twig'
        );
    }

    public function setupCheck(Request $request): JsonResponse
    {
        $data = [
            'testParam' => $request->get('testParam')
                ? (int) $request->get('testParam')
                : null
        ];
        return new JsonResponse(
            $data
        );
    }


}
