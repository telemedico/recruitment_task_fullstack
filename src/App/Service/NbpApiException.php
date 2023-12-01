<?php
declare(strict_types=1);

namespace App\Service;

use Symfony\Contracts\HttpClient\ResponseInterface;
use \Exception;

class NbpApiException extends Exception {

    private ResponseInterface $response;

    public function __construct(ResponseInterface $response) {
        $this->response = $response;
    }

    public function getResponse() : ResponseInterface  {
        return $this->response;
    }
}