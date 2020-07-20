<?php

/**
 * This file is part of the planb project.
 *
 * (c) jmpantoja <jmpantoja@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace LearnWords\Infrastructure\UI\Web\Controller;


use Symfony\Component\HttpFoundation\JsonResponse;

final class HomeController
{

    public function __invoke(): JsonResponse
    {

        return new JsonResponse([
            'success' => 'ok',
        ]);
    }
}
