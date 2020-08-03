<?php

declare(strict_types=1);

namespace LearnWords\Infrastructure\UI\Admin\Tag;

use LearnWords\Infrastructure\Domain\Word\Dto\TagDto;
use PlanB\Edge\Infrastructure\Sonata\Admin\Admin;

final class TagAdmin extends Admin
{
    public function getDtoClass(): string
    {
        return TagDto::class;
    }
}
