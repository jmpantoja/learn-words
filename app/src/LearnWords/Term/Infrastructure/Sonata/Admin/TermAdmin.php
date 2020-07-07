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

namespace LearnWords\Term\Infrastructure\Sonata\Admin;


use LearnWords\Term\Application\Delete\DeleteTerm;
use LearnWords\Term\Application\Save\SaveTerm;
use PlanB\Edge\Domain\Entity\EntityInterface;
use PlanB\Edge\Infrastructure\Sonata\Admin\Admin;

final class TermAdmin extends Admin
{
    public function saveCommand(array $input, ?EntityInterface $entity): SaveTerm
    {
        return SaveTerm::make($input, $entity);
    }

    public function deleteCommand(EntityInterface $entity): DeleteTerm
    {
        return DeleteTerm::make($entity);
    }
}
