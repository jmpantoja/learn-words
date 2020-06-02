<?php

namespace LearnWords\Site\Admin\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class HomeController
{
    public function __invoke(Request $request): JsonResponse
    {

        return new JsonResponse(
            [
                'env' => $_SERVER['APP_ENV'],
                'success' => 'ok',
                'site' => 'admin'
            ]
        );
    }
}
