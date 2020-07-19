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


use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use LearnWords\Domain\Tag\Tag;
use LearnWords\Domain\Term\TermRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

final class HomeController
{
    /**
     * @var DataPersisterInterface
     */
    private DataPersisterInterface $dataPersister;
    /**
     * @var TermRepositoryInterface
     */
    private TermRepositoryInterface $termRepository;

    public function __construct(DataPersisterInterface $dataPersister, TermRepositoryInterface $termRepository)
    {
        $this->dataPersister = $dataPersister;
        $this->termRepository = $termRepository;
    }

    public function __invoke()
    {
        $term = $this->termRepository->find('0ac5bc68-c7a2-11ea-9d05-0242ac140004');

//        $term->addTag(uniqid('tag-'));
        $term->addTag('hola');

        $this->dataPersister->persist($term);

        $tags = array_map(function (Tag $tag) {
            return $tag->getTag();

        }, $term->getTags());

        return new JsonResponse([
            'success' => 'ok',
            'termId' => (string)$term->getId(),
            'tags' => $tags
        ]);
    }
}
