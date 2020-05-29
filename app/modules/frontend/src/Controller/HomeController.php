<?php

namespace LearnWords\Module\Frontend\Controller;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class HomeController
{
    public function __invoke(Request $request): JsonResponse
    {
        //throw new Exception('s');
        return new JsonResponse(
            [
                'env' => $_SERVER['APP_ENV'],
                'success' => 'ok',
                'whoami' => exec('whoami'),
            ], 200, [
                'cache-control' => 'public'
            ]
        );
    }
}
