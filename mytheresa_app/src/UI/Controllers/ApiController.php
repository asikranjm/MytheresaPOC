<?php
declare(strict_types=1);

namespace App\UI\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class ApiController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    protected function createResponse(array $data, int $status = 200): JsonResponse
    {
        $response = ['data' => $data];
        return new JsonResponse($response, $status, $this->getDefaultHeaders());
    }

    protected function notFoundResponse(): JsonResponse
    {
        return new JsonResponse([], Response::HTTP_NOT_FOUND, $this->getDefaultHeaders());
    }

    private function getDefaultHeaders(): array
    {
        return [
            'Content-Type'                 => 'application/json',
            'Access-Control-Allow-Methods' => 'GET, POST',
            'Access-Control-Allow-Headers' => 'Content-Type',
            'Vary'                         => 'Origin',
        ];
    }
}
