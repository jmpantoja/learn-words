<?php

namespace LearnWords\Module\Frontend\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class HomeController
{
    public function __invoke(Request $request): JsonResponse
    {
        return new JsonResponse(
            [
                'success' => 'ok'
            ]
        );
    }
}
