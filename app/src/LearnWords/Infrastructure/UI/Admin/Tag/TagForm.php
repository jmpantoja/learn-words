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

namespace LearnWords\Infrastructure\UI\Admin\Tag;


use LearnWords\Domain\Dictionary\Tag;
use LearnWords\Infrastructure\Domain\Dictionary\Dto\TagDto;
use PlanB\Edge\Infrastructure\Sonata\Configurator\FormConfigurator;
use PlanB\Edge\Infrastructure\Symfony\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;


final class TagForm extends FormConfigurator
{
    static public function attachTo(): string
    {
        return TagAdmin::class;
    }

    public function configure(Tag $word = null): void
    {
        $this->add('tag', TextType::class);
    }

    public function transform(?object $data)
    {
        if (null === $data) {
            return null;
        }
        return TagDto::fromObject($data);
    }
}
